<?php

namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Collection;
use Auth;
use Excel;
use App\DatasetsList as DL;
use MySQLWrapper;
use DB;
use File;
class ImportdatasetController extends Controller
{

    function uploadDataset(Request $request){


	   $validate = $this->validateRequst($request);
        
    	if($validate['status'] == 'false'){
    		$response = ['status'=>'error','errors'=>$validate['errors']];
    		return $response;
    	}

        if($request->source == 'file'){
           
             if($request->file('file')->getClientOriginalExtension()=='sql' )
             {
                    $path = 'sql'; 
             }else{
                    $path = 'datasets';
                }
            try {
                 if(!in_array($request->file('file')->getClientOriginalExtension(),['csv','sql','xlsx','xls'])){
                    return ['status'=>'error','records'=>'File type not allowed!'];
                }
            } catch (Exception $e) {
                return ['status'=>'error','records'=>'Please Select a File to Upload'];
            }
            $file = $request->file('file');
            if($file->isValid()){

                $filename = date('Y-m-d-H-i-s')."-".$request->file('file')->getClientOriginalName();
                $uploadFile = $request->file('file')->move($path, $filename);
                $filePath = $path.'/'.$filename;
            }
        }
        
        if($request->source == 'file_server'){
            $filePath = $request->filepath;
            $filep = explode('/',$filePath);
            $filename = $filep[count($filep)-1];
        }

        if($request->source == 'url'){
            $filePath = $request->fileurl;
            $filep = explode('/',$filePath);
            $filename = $filep[count($filep)-1];
        }

        if($request->add_replace == 'newtable'){
            $result = $this->storeInDatabase($filePath, $request->dataset_name, $request->source, $filename);
        }elseif($request->add_replace == 'append'){
            $result = $this->appendDataset($request->dataset_name, $request->source, $filename, $filePath, $request);
        }elseif($request->add_replace == 'replace'){
            //$result = $this->replaceDataset($request, $request->dataset_name, $filePath);
        }

        if($result['status'] == 'true'){
            
			$response = ['status'=>'success','message'=>$result['message'],'id'=>$result['id']];
			return $response;
    		
        }else{
            $response = ['status'=>'error','message'=>$result['message']];
            return $response;
        }

    }
    protected function validateRequst($request){
        $errors = [];
        if($request->has('source') && $request->source != ''){
            switch($request->source){
                case'file':
                    if($request->file('file') == '' || empty($request->file('file')) || $request->file('file') == null){
                        $errors['message'] = 'File field should not empty!';
                    }
                break;
                case'file_server':
                    if(!$request->has('filepath') || $request->filepath == ''){
                        $errors['message'] = 'File path should not empty!';
                    }
                break;
                case'url':
                    if(!$request->has('fileurl') || $request->fileurl == ''){
                        $errors['message'] = 'File url should not empty!';
                    }
                break;
            }
        }else{
            $errors['message'] = 'Required fields are missing!';
        }
        
        /*if($request->format == 'undefined' || empty($request->format) || $request->format  == null){
            $errors['format'] = 'Please select file format';
        }*/

    	if($request->add_replace == 'undefined' || empty($request->add_replace) || $request->add_replace  == null){
    		$errors[] = 'Please select file format!';
    	}
    	if($request->add_replace == 'replace' || $request->add_replace == 'append'){
    		if($request->with_dataset == '' || $request->with_dataset == 'undefined' || empty($request->with_dataset)){
    			$errors['message'] = 'Please select dataset to '.$request->add_replace;
    	   }
    	}
        
    	if(count($errors) >= 1){
    		$return = ['status' => 'false','errors'=>$errors];
    		return $return;
    	}else{
    		$return = ['status' => 'true','errors'=>[]];
    		return $return;
    	}
    }

    public function runSqlFile($filepath ,$name ,$origName){
           
        $sql =  file_get_contents($filepath);
        $lines = explode("\n", $sql); 
        $create_table = $status = $output = ""; 
        $linecount = count($lines); 
        $create=$next=0;
        for($i = 0; $i < $linecount; $i++){

            if(starts_with($lines[$i], "CREATE")){

                $create_table .= $lines[$i];
                $table = explode(' ', $lines[$i]); 
                $tableName = str_replace('`', '', $table[2]); 
                $status .=1;
                $create=$i;
            }
            if(starts_with($lines[$i],'--')){
                $create =0;
            }
            if($create>0 && $create<$i){
                $create_table .= $lines[$i];
            }
            if(starts_with($lines[$i], "INSERT") ){

                $output .= $lines[$i];
                $next = $i;
                $status .=2;
            }
            if($next>0 && $i>$next){
                if(str_contains($lines[$i], ['--','ALTER','ADD','/*','MODIFY'])){ 
                    $next=0;
                }
                else{
                    $output .= $lines[$i];
                }
            } 
        }            
        try{
            DB::select($create_table);

        }catch(\Exception $e){

            if($e->getCode() =="42S01"){

            }
        }
        if($status !='12'){
            return ['status'=>'false','id'=>'','message'=>'Not exist create & Inset'];
        } 
        else{   
            try{                    
                                
                DB::select($output);
                $model = new DL;
                $model->dataset_table = $tableName;
                $model->dataset_name = $name;
                $model->dataset_file = $filepath;
                $model->dataset_file_name = $origName;
                $model->user_id = Auth::user()->id;
                $model->uploaded_by = Auth::user()->name;
                $model->save();   
                return ['status'=>'true','id'=>'','message'=>'Sql File  Import successfully!'];

             }catch(\Exception $e){ 
                if($e->getCode()==23000){

                    return ['status'=>'false','id'=>'','message'=>'Duplicate entry'];                                           
                }  
            }
        }       
    }

    public function getColumns($id){
        try{
            $model = DL::where('id',$id)->first();
             $datasetTable  = DB::table($model->dataset_table)->limit(1)->first();
            if(empty($datasetTable)){
                return ['status'=>'error','message'=>'no data found!'];
            }
            $columnsArray = [];
            $index = 0;
            foreach($datasetTable as $key => $value){
                if($index != 0){
                    $columnsArray[$key] = $value;
                }
                $index++;
            }

            return ['status'=>'sucess','data'=>['columns'=>$columnsArray,'dataset_id'=>$model->id,'validated'=>$model->validated, 'dataset_columns'=>  json_decode($model->dataset_columns)], 'dataset_name' => $model->dataset_name];
        }catch(\Exception $e){
            return ['status'=>'error','message'=>'no data found!'];

        }

    }

    protected function storeInDatabase($filename, $origName, $source, $orName){
        
        $filePath = $filename;
        if($source == 'url'){
            $randName = 'downloaded_dataset_'.time().'.csv';
            $path = 'datasets/';
            copy($filename, $path.$randName);
            $filePath = 'datasets/'.$randName;
        }
        if(!File::exists($filePath)){
            return ['status'=>'false','id'=>'','message'=>'File not found on given path!'];
        }
        if(File::extension($filename)=="sql"){
           
            return $this->runSqlFile($filePath ,$origName , $orName);
            // return ['status'=>'true','id'=>'','message'=>'Dataset sql upload successfully!'];
        }elseif(File::extension($filename)=="xlsx" || File::extension($filename)=="xls"){
            $tableName = 'data_table_'.time();
            $columns = [];
            $assoc = [];
            $finalArray = [];
            $headers = [];
            $data = Excel::load($filePath, function($reader){ })->get();
            foreach($data as $key => $value){
                $FileData[] = $value->all();
            }
            $i = 1;
            foreach($FileData[0] as $key  => $value){
                $headers[] = $key;
                $c = 'column_' . $i++;
                $assoc[] = $c;
                $columns[] = "`{$c}` TEXT NULL";
            }
            
            foreach($FileData as $values){
                $finalArray[] = array_combine($assoc, array_values($values));
            }
            $headers = array_combine($assoc, array_values($headers));
            DB::select("CREATE TABLE `{$tableName}` ( " . implode(', ', $columns) . " ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            DB::select("ALTER TABLE `{$tableName}` ADD `id` INT(100) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Row ID' FIRST");
            DB::table($tableName)->insert($headers);
            DB::table($tableName)->insert($finalArray);
            $model = new DL;
            $model->dataset_table = $tableName;
            $model->dataset_name = $origName;
            $model->dataset_file = $filePath;
            $model->dataset_file_name = $orName;
            $model->user_id = Auth::user()->id;
            $model->uploaded_by = Auth::user()->name;
            $model->dataset_records = '{}';
            $model->save();
            return ['status'=>'true','id'=>$model->id,'message'=>'Dataset upload successfully!'];
        }else{

            DB::beginTransaction();
            $model = new MySQLWrapper();
            $tableName = 'data_table_'.time();
            
            $result = $model->wrapper->createTableFromCSV($filePath,$tableName,',','"', '\\', 0, array(), 'generate','\r\n');
            
            if($result){
                $model = new DL;
                $model->dataset_table = $tableName;
                $model->dataset_name = $origName;
                $model->dataset_file = $filePath;
                $model->dataset_file_name = $orName;
                $model->user_id = Auth::user()->id;
                $model->uploaded_by = Auth::user()->name;
                $model->dataset_records = '{}';
                $model->save();
                DB::commit();
                return ['status'=>'true','id'=>$model->id,'message'=>'Dataset upload successfully!'];
            }else{
                DB::rollback();
                return ['status'=>'false','id'=>'','message'=>$result['error']];
            }
        }
    }

    protected function replaceDataset($request, $origName, $filename){

        ini_set('memory_limit', '2048M');
    	$FileData = [];
    	$data = Excel::load($filename, function($reader){ })->get();

    	foreach($data as $key => $value){
            $FileData[] = $value->all();
    	}
		$model = DL::find($request->with_dataset);
		$model->dataset_name = $origName;
        $model->dataset_records = json_encode($FileData);
		$model->user_id = Auth::user()->id;
		$model->uploaded_by = Auth::user()->name;
		$model->dataset_columns = null;
		$model->validated = 0;
		$model->save();

  		if($model){
  			return ['status'=>'true','id'=>$model->id,'message'=>'Dataset replaced successfully!'];
  		}else{
  			return ['status'=>'false','message'=>'unable to replace dataset!'];
  		}
    }
    
    protected function appendDataset($datasetName, $source, $filename, $filePath, $request){
       
        if($source == 'url'){
            $randName = 'downloaded_dataset_'.time().'.csv';
            $path = 'datasets/';
            copy($filename, $path.$randName);
            $filePath = 'datasets/'.$randName;
        }

        if(!File::exists($filePath)){
            return ['status'=>'false','id'=>'','message'=>'File not found on given path!'];
        }

        $tableName = 'table_temp_'.rand(5,1000);
        $model_DL = DL::find($request->with_dataset);
        $oldTable = DB::table($model_DL->dataset_table)->get();
        if(File::extension($filePath)=="xlsx" || File::extension($filePath)=="xls"){
            $assoc = [];
            $finalArray = [];
            $headers = [];
            $data = Excel::load($filePath, function($reader){ })->get();
            foreach($data as $key => $value){
                $FileData[] = $value->all();
            }
            $i = 1;
            foreach($FileData[0] as $key  => $value){
                $headers['column_'.$i] = $key;
                $c = 'column_' . $i;
                $assoc[] = $c;
                $i++;
            }
            
            foreach($FileData as $values){
                $finalArray[] = array_combine($assoc, array_values($values));
            }
            unset($oldTable[0]->id);
            $new = (array)$headers;
            $old = (array)$oldTable[0];
            if($new != $old){
                return ['status'=>'false','message'=>'File columns are note same!'];
            }
            DB::table($model_DL->dataset_table)->insert($finalArray);
        }else{
            $model = new MySQLWrapper;
            $result = $model->wrapper->createTableFromCSV($filePath,$tableName,',','"', '\\', 0, array(), 'generate','\r\n');
            $tempTableData = DB::table($tableName)->get();
            
            $oldColumns = [];
            $new = (array)$tempTableData[0];
            $old = (array)$oldTable[0];
            
            if($new != $old){
                DB::select('DROP TABLE '.$tableName);
                return ['status'=>'false','message'=>'File columns are note same!'];
            }
            unset($new['id']);

            $appendColumns = implode(',', array_keys($new));
            DB::select('INSERT INTO `'.$model_DL->dataset_table.'` ('.$appendColumns.') SELECT '.$appendColumns.' FROM '.$tableName.' WHERE id != 1;');
            DB::select('DROP TABLE '.$tableName);
        }
        
        return ['status'=>'true','message'=>'Dataset updated successfully!!', 'id'=>$model_DL->id];
    }
}
