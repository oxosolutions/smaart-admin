<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DatasetsList as DL;
use Carbon\Carbon;
use Auth;
use DB;
use App\GlobalSetting as GS;
use Excel;
class DatasetsController extends Controller
{

    public function create_dataset(Request $request)
    {
        $org_id = Auth::user()->organization_id;
        $tableName = $org_id.'_data_table_'.time();
        $dl = new DL;
        $dl->dataset_name = $request->dataset_name;
        $dl->dataset_table = $tableName;
        $dl->user_id = Auth::user()->id;
        $dl->save();

        $dataset_columns = json_decode($request->dataset_columns,true);

        $i=1;
        if(empty($dataset_columns)){
            $dataset_columns = [];
            for($len = 1; $len <= $request->number_of_columns; $len++){
                $c = 'column_' . $len;
                $assoc[] = $c;
                $columns[] = "`{$c}` TEXT NULL";
                $dataset_columns[$c] = 'header_'.$len;
            }
        }else{
            foreach($dataset_columns as $key  => $value){                       
                $c = 'column_' . $i++;
                $assoc[] = $c;
                $columns[] = "`{$c}` TEXT NULL";
            }
        }
        
        DB::select("CREATE TABLE `{$tableName}` ( " . implode(', ', $columns) . " ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
                    DB::select("ALTER TABLE `{$tableName}` ADD `id` INT(100) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Row ID' FIRST");
                    DB::table($tableName)->insert($dataset_columns);
        return ['status'=>'success' , 'message'=>"Create & Insert Successfully ",'dataset_id'=>$dl->id];       
    }


    public function updateDataSetName(Request $request)
    {
        try{
            DL::where('id',$request->id)->update(['dataset_name'=>$request->dataset_name]);
            return ['status'=>"success","message"=>"Successfully Update Dataset Name!" ];
        }catch(\Exception $e)
        {
            return ['status'=>'error', 'message'=>'something goes wrong try Again.'];
        }
    }

    function getDatasetsList(){
        $list = DL::orderBy('id', 'DESC')->get();
        $responseArray = [];
        $index = 0;
        foreach($list as $key => $value){

            $responseArray[$index]['dataset_id'] = $value->id;
            $responseArray[$index]['dataset_name'] = $value->dataset_name;
            $responseArray[$index]['validated'] = $value->validated;
            $responseArray[$index]['dataset_columns'] = $value->dataset_columns;
            $responseArray[$index]['created_date'] = $value->created_at->format('Y-m-d H:i:s');
            $index++;
        }
        return ['data'=>$responseArray];
    }

    protected function datasetSetting()
    {
        $gs = GS::where('meta_key','dataset_setting')->first();
        $data = json_decode($gs->meta_value);
        if($data->activate == 'true')
        { 
           return $data->num_row;
        }
        else{
            return 500;
        }
    }

    public function getDatasets($id,$skip = 0){
        
        $datasetDetails = DL::find($id);
        $limit = $this->datasetSetting();
        $datasetTable = DB::table($datasetDetails->dataset_table)->skip($skip)->take($limit)->get();
        if(empty($datasetTable)){
            return ['status'=>'success','records'=>[]];
        }

        $responseArray = [];
        $responseArray['dataset_id'] = $id;
        $responseArray['dataset_name'] = $datasetDetails->dataset_name;
        $responseArray['records'] = json_decode($datasetTable);
        $totalRecords = DB::table($datasetDetails->dataset_table)->count();
        return ['status'=>'success','records'=>$responseArray, 'total'=>$totalRecords,'skip'=>$skip,'limit'=>$limit];
    }

    public function getDatasetsColumnsForSubset($id){
        $datasetDetails = DL::find($id);
        $datasetTable = DB::table($datasetDetails->dataset_table)->take($this->datasetSetting())->get();
        
        if(empty($datasetTable)){
            return ['status'=>'success','records'=>[]];
        }

        $responseArray = [];
        $responseArray['dataset_id'] = $id;
        $responseArray['dataset_name'] = $datasetDetails->dataset_name;
        $responseArray['records'] = json_decode($datasetTable);
        $totalRecords = DB::table($datasetDetails->dataset_table)->count();
        return ['status'=>'success','records'=>$responseArray];
    }

    public function getFormatedDataset($id){

        $model = DL::find($id);
        if ($model == '' || empty($model)){
            return ['status'=>'error','records'=>'no records found'];
        }else{
            $records = DB::table($model->dataset_table)->get();
            if(empty($records)){
                return ['status'=>'error','message'=>'Dataset columns not defined yet!'];
            }
            $headers = [];
            $index = 0;
            $recordsCol = (array)$records[0];
            $columnsArray = json_decode($model->dataset_columns); //value will be string, numeric, date
            foreach($columnsArray as $colKey => $colVal){
                
                $headers[$index]['id'] = $colKey;
                $headers[$index]['label'] = $recordsCol[$colKey];
                $headers[$index]['type'] = $colVal;
                
                $index++;
            }
            //unset($records[0]);
            return ['status'=>'success','data'=>['column'=>$headers,'records'=>$records]];
        }
    }

    protected function validateUpdateColumns($request){

        if($request->has('id') && $request->has('columns')){
            $return = ['status'=>'true','message'=>''];
        }else{
            $return = ['status'=>'false','message'=>'Required fields are missing!'];
        }
        return $return;
    }

    public function SavevalidateColumns(Request $request){
  
        $result = $this->validateUpdateColumns($request);
        if($result['status'] == 'false'){

            return ['status'=>'error','message'=>$result['message']];
        }
        $model = DL::find($request->id);

        $newColumns = json_decode($request->create_columns);
        $orgColumns = (array)json_decode($request->columns);

        //dump($newColumns);

        if(!empty($newColumns)){
            $orgColumns = $this->createNewColumns($request->create_columns, $model->dataset_table, $orgColumns);
            if($orgColumns == false){
                return ['status'=>'error','message'=>'Some error occurs during query execution!'];
            }
        }


        $orgColumns = json_encode($orgColumns);
        if(!empty($model)){

            $model->dataset_columns = $orgColumns;
            $model->save();
            return ['status'=>'sucess','message'=>'Columns updated successfully!','updated_id'=>$model->id];
        }else{

            return ['status'=>'error','message'=>'No record found with given id!'];
        }
    }

    protected function createNewColumns($columns, $table, $orgColumns){



        foreach(json_decode($columns) as $key => $value){

            $columnsList = DB::select('SHOW COLUMNS FROM `'.$table.'`');
            $colCount = rand(1000,2000);//count($columnsList)-1;

            $column_name =  $value->col_name;
            $excute_formula =  $value->formula;
            $operation = $value->operation;

            try{
                DB::select('ALTER TABLE `'.$table.'` ADD COLUMN column_'.$colCount.' TEXT NULL AFTER '.$value->col_after.';');
                DB::table($table)->update(['column_'.$colCount => $operation]);

                if($excute_formula){
                    $rawdata = Excel::create('Filename', function($excel) use ($operation,$table,$colCount) {
                        $excel->sheet('Sheetname', function($sheet) use ($operation,$table,$colCount){
                            $data = DB::select('SELECT * FROM `'.$table.'`');
                            foreach ($data as $key => $data_row) {
                                $data_row = (Array)$data_row;
                                if($key==0)
                                {
                                    $count_colum = 802;//count($data_row); 
                                     $key_map = array("A","B","C","D","E","F","G","H","I","J","k","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

                                     $first_prefix= $first_index = $second = null;

                                         for($i=0; $i<$count_colum; $i++)
                                         {
                                          if($i<26)
                                          {
                                            $new_data[] =  $key_map[$i];
                                          }
                                          elseif(($i % 26)==0)
                                          {
                                            $second_index = ($i / 26) * 26;
                                            $first_index = ($i / 26);
                                            if($first_index >26)
                                            {
                                                $first_prefix = $key_map[0];
                                            if($first_index >52)
                                            {
                                              $first_prefix = $key_map[1];
                                            }

                                            $first_index = $first_index-26; 
                                            }
                                          }

                                          if($first_index !=null && $second_index !=null)
                                          {
                                            $next_index = $i - $second_index;
                                            $new_data[] = $first_prefix.''.$key_map[$first_index - 1].''.$key_map[$next_index];
                                          }
                                         
                                         }
                                         dump($new_data);   
                                   
                                }
                                $row_id = $data_row['id']; 

                                $data_row['column_'.$colCount] = str_replace("$",$row_id,$data_row['column_'.$colCount]);
                                $column_keys = array_keys($data_row);
                                $search = array_search('column_'.$colCount , $column_keys);

                               

                                


                                $sheet->row($row_id, $data_row);
                                $column_value = $sheet->getCell($key_map[$search].$row_id)->getCalculatedValue();
                                //$operation_val[] = $column_value;
                                DB::table($table)->where(['id'=>$row_id])->update(['column_'.$colCount => $column_value]);
                            }

                        });
                    });
                }

                DB::table($table)->where(['id'=>1])->update(['column_'.$colCount => $column_name]);
                $orgColumns['column_'.$colCount] = $value->col_type;

            }catch(\Exception $e){
                throw $e;
            }
        }

        dump($orgColumns);

        return $orgColumns;


            // try{
            //     DB::select('ALTER TABLE `'.$table.'` ADD COLUMN column_'.$colCount.' TEXT NULL AFTER '.$value->col_after.';');


            //     DB::table($table)->update(['column_'.$colCount => $value->operation]);



            //     DB::table($table)->where(['id'=>1])->update(['column_'.$colCount => $value->col_name]);
            //     $orgColumns['column_'.$colCount] = $value->col_type;
            //     }catch(\Exception $e){
            //         return false;
            //    }
            // }
            


            //if($value->formula == true){
                //try{

                   // DB::select('UPDATE `'.$table.'` `set column_'.$colCount.'` = "'.$value->operation.'"  where id > 1');
                    //die;
                    // DB::select('UPDATE `'.$table.'` set column_'.$colCount.' = DATEDIFF(STR_TO_DATE(`'.$value->col_one.'`,"%m/%d/%Y"),STR_TO_DATE(`'.$value->col_two.'`,"%m/%d/%Y")) where id > 1 and '.$value->col_two.' REGEXP "[0-9]" and '.$value->col_one.' REGEXP "[0-9]"');
               // }catch(\Exception $e){
                    //return false;
               // }
                
            //}
            
        //}
        
    }

    protected function checkIfColumnExistinTable(){

        $result = DB::select('SHOW COLUMNS FROM `data_table_1482944931`');
        $columns = [];
        foreach($result as $key => $value){
            $columns[] = $value->Field;
        }

        return $columns;
    }


    public function deleteDataset($id){

        $model = DL::find($id);   
           if(!empty($model)){
             if($model->dataset_file!=Null)
                {   
                   try{
                        unlink($model->dataset_file);
                   }catch(\Exception $e){
                        
                   }
                }
            $model->delete();
            DB::select('DROP TABLE `'.$model->dataset_table.'`');
            return ['status'=>'success','message'=>'Successfully deleted!','deleted_id'=>$id];
        }else{

            return ['status'=>'error','message'=>'No dataset find with this id'];
        }
    }

    public function saveEditedDatset(Request $request){

        $validate = $this->validateEditDatasetRequest($request);
        if(!$validate){
            return ['status'=>'error','message'=>'Required fields are missing!'];
        }
        $model = DL::find($request->dataset_id);
        $table = $model->dataset_table;

        foreach(json_decode($request->records) as $key => $data){
            $columns = [];
            $id = '';
            foreach($data as $colKey => $colValue){
                if($colKey != 'id'){
                    $columns[$colKey] = $colValue;
                }else{
                    $id = $colValue;
                }
            }
            if($id == ''){
                DB::table($table)->insert($columns);
            }else{
                DB::table($table)->where('id',$id)->update($columns);
            }
            
        }
        if(!empty(json_decode($request->deletedRows))){
            DB::table($table)->whereIn('id',json_decode($request->deletedRows))->delete();
        }
        return ['status'=>'success','message'=>'Dataset updated successfully!','dataset_id'=>$request->dataset_id];
    }

    protected function validateEditDatasetRequest($request){

        if($request->has('dataset_id') && $request->has('records')){

            return true;
        }else{

            return false;
        }
    }

    public function saveNewSubset(Request $request){
        
        $validate = $this->validateNewSubset($request);
        if(!$validate){
            return ['status'=>'error','message'=>'Required fields are missing!!'];
        }
        $deleteStatus = 0;
        $model = DL::find($request->dataset_id);
        $tableName = 'data_table_'.time();
        $columns = array_keys((array)json_decode($request->subset_columns));
        $where_in = '';
        if($request->column_key != 'undefined' && $request->column_val != 'undefined'){
            $in = explode(',',$request->column_val);
            $in_vals = "";
            $index = 1;
            foreach($in as $k => $v){
                $in_vals .= "'".$v."'";
                if($index != count($in)){
                    $in_vals .= ",";
                }
                $index++;
            }
            $where_in = 'where '.$request->column_key.' not in('.$in_vals.') and id != 1';
            DB::select("CREATE TABLE `{$tableName}` as SELECT  ".implode(',', $columns)." FROM ".$model->dataset_table.";");
            $deleteStatus = 1;
            //DB::select("CREATE TABLE `{$tableName}` as SELECT  ".implode(',', $columns)." FROM ".$model->dataset_table." ".$where_in.";");
        }else{
            DB::select("CREATE TABLE `{$tableName}` as SELECT  ".implode(',', $columns)." FROM ".$model->dataset_table.";");
        }
        DB::select("ALTER TABLE `{$tableName}` ADD  `id` INT(100) PRIMARY KEY AUTO_INCREMENT FIRST;");
        if($deleteStatus == 1){
            DB::select("DELETE FROM `{$tableName}` ".$where_in.";");
        }
        $model = new DL();
        $model->dataset_name = $request->subset_name;
        $model->dataset_records = '{}';
        $model->dataset_table = $tableName;
        $model->user_id = Auth::user()->id;
        $model->uploaded_by = Auth::user()->name;
        $model->save();

        return ['staus'=>'success','message'=>'Subset saved successfully','dataset_id'=>$model->id];
    }
    protected function validateNewSubset($request){

        if($request->has('subset_name') && $request->has('subset_columns') && $request->has('dataset_id')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * [filterIncorrectDataFromDataset for validate dataset data according to its type]
     * @param  [integer] $datasetID [dataset id]
     * @return [json] [will return json response]
     * @link it will use in SDGINDIA dataset.controller.js
     */
    public function filterIncorrectDataFromDataset($datasetID){

        $model = DL::find($datasetID);
        if(empty($model)){
            return ['status'=>'error','message'=>'No dataset found!','code'=>500];
        }
        if($model->validated == 0){
            return ['status'=>'error','message'=>'Dataset not validated!','code'=>501];
        }
        $recordsList = [];
        $datasetColumns = json_decode($model->dataset_columns);
        $datasetRecords = json_decode($model->dataset_records);
        foreach ($datasetRecords as $setKey => $setValue) {
            $singleRow = [];
            foreach ($datasetColumns as $Colkey => $ColValue) {
                if(gettype($setValue[$Colkey]) == $ColValue){
                    $singleRow[$Colkey] = $setValue[$Colkey];
                }else{
                    $singleRow[$Colkey] = "<>".$setValue[$Colkey]."<>";
                }
            }
        }
    }

    public function validateColums($id){

        $model = DL::find($id);
        if($model->dataset_columns == '' || $model->dataset_columns == null){
            return ['status'=>'error','message'=>'dataset not defined yet!','defined'=>'false'];
        }
        
        if(!empty($model)){
            
            $wrongDataRows = [];
            $datasetTable = DB::table($model->dataset_table)->where('id','!=',1)->get()->toArray();
            $columnsTypeArray = (array)json_decode($model->dataset_columns);
            foreach($datasetTable as $key => $row){// for each row
                $tempCol = [];
                $error = false;
                foreach($row as $colKey => $colVal){// for each column
                    
                    if(array_key_exists($colKey,$columnsTypeArray)){
                        $type = $columnsTypeArray[$colKey];
                        if($type == 'areacode'){
                            $type = 'string';
                        }
                        if($type != 'date'){
                            if($type == 'integer'){
                                if(!is_numeric($colVal)){
                                    $error = true;
                                    $tempCol[$colKey] = '<span>'.$colVal.'</span>';
                                }else{
                                    $tempCol[$colKey] = $colVal;
                                }
                            }
                            /*if($type == 'string'){
                               if(is_numeric($colVal)){
                                    $wrongDataRows[] = $row;
                                    break;
                               }
                            }*/
                        }else{
                            $dataType = (bool)strtotime($colVal);
                            if($dataType != true){
                                $wrongDataRows[] = $row;
                                break;
                            }
                        }
                    }
                }
                if($error == true){
                    foreach($row as $key => $col){
                        if(!array_key_exists($key, $tempCol)){
                            $tempCol[$key] = $col;
                        }
                    }
                    $wrongDataRows[] = $tempCol;
                }
            }
            if(empty($wrongDataRows)){
                $this->updateDatasetAsValidated($id,1);
            }else{
                $this->updateDatasetAsValidated($id,0);
            }
            return ['status'=>'success','message'=>'Columns valudated successfully!','wrong_rows'=>$wrongDataRows,'dataset_name'=>$model->dataset_name];
        }else{
            return ['stauts'=>'error','message'=>'No dataset found!'];
        }
    }

    protected function updateDatasetAsValidated($dataset_id,$status){

        $model = DL::find($dataset_id);
        $model->validated = $status;
        $model->save();
        return true;
    }

    public function staticDatsetFunction(){

        $columns = DB::table('data_table_1483033420')->take(1)->get();
        return ['status'=>'success','columns'=>$columns[0]];
    }

    public function dataForGenerateVisual(Request $request){

        $columsArray = [];
        $value = str_replace('"', '', $request->columns);
        $model = DB::table('data_table_1483033420')->select($value)->where('id','!=',1)->groupBy($value)->get();
        $columnsArray[] = $value;
        foreach($model as $iKey => $ivalue){
            $newModel = DB::table('data_table_1483033420')->where($value,$ivalue->{$value})->count();
            $columnsArray[$value][$ivalue->{$value}] = $newModel;
        }
        
        return ['status'=>'success','columns'=>$columnsArray];
    }

    

}
