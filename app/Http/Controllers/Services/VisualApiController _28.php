<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneratedVisual as GV;
use App\DatasetsList as DL;
use DB;
use App\GeneratedVisualQuerie as GVQ;
use Auth;
use App\GlobalSetting as GS;
class VisualApiController extends Controller
{
    public function visualList(){

        $model = GV::all();
        $resultArray = [];
        foreach($model as $key => $value){
            $tempArray = [];
            $tempArray['id'] = $value->id;
            $tempArray['visual_name'] = $value->visual_name;
            try{
                $tempArray['dataset'] = array('dataset_id'=>$value->dataset_id,'dataset_name'=>$value->datasetName->dataset_name);
            }catch(\Exception $e){
                $tempArray['dataset'] = [];
            }
            
            $tempArray['created_by'] = $value->createdBy->name;
            $tempArray['created_at'] = $value->created_at->format('Y-m-d H:i:s');
            $resultArray[] = $tempArray;
        }

        return ['status'=>'success','records'=>$resultArray];
    }

    /*public function visualById($id){

    	$model = GV::find($id);
    	$value = $model;
		$tempArray = [];
		$tempArray['id'] = $value->id;
		$tempArray['visual_name'] = $value->visual_name;
		$tempArray['dataset_id'] = $value->dataset_id;
		$datasetData = DL::find($value->dataset_id);
		$dataTableData = DB::table($datasetData->dataset_table)->select(json_decode($value->columns))->where('id',1)->first();
		$columnArray = [];
		foreach(json_decode($value->columns) as $colKey => $colValue){
			$columnArray[$colValue] = $dataTableData->{$colValue};
		}
        unset($dataTableData->id);
        $GVQModel = GVQ::where('visual_id',$id)->first();
		$tempArray['columns'] = $columnArray;
        $tempArray['all_columns'] = $dataTableData;
		$tempArray['gen_columns'] = json_decode($value->query_result);
        $tempArray['filters'] = json_decode($GVQModel->query);
		$tempArray['created_by'] = $value->createdBy->name;
		$tempArray['created_at'] = $value->created_at->format('Y-m-d H:i:s');

    	return ['status'=>'success','records'=>$tempArray];
    }*/

    /*public function visualById($id){
        $model = GV::find($id);
        $tempArray = [];
        $tempArray['id'] = $model->id;
        $ColumnsCount = json_decode($model->filter_counts);
        $data_columns = json_decode($model->columns);
        $datasetData = DL::find($model->dataset_id);
        $dataTableData = DB::table($datasetData->dataset_table)->where('id',1)->first();
        
        $columnsArray = [];
        foreach ($ColumnsCount as $key => $col) {
            $columnsArray[$key]['column_name'] = $dataTableData->{$key};
            foreach($col as $ikey => $icol){
                $columnsArray[$key]['column_data'][] = $icol->{$key};
            }
        }
        $response['filters'] = $columnsArray;
        foreach ($data_columns as $columnKey => $columnValue) {
            $response['column_names'][] = $dataTableData->{$columnValue};
        }
        $dataArary = [];
        foreach(json_decode($model->query_result) as $key => $value){
            $dataArary[$key]['column_name'] = $dataTableData->{$key};
            $dataArary[$key]['column_data'] = $value;
        }
       
        //Process Data 
        $temp_array = [];
        foreach($dataArary as $key => $value){
            $column_name = $value['column_name'];
            $column_data = [];
            foreach ( $value['column_data'] as $column_key => $column_value ) {
                $column_data[$column_value->$key] = $column_value->count;
                
            }
            $temp_array[$column_name] = $column_data;
        }
        
        //Process Data according to google charts
        $data_keys = [];
        foreach($temp_array as $key => $value){
            foreach ( $value as $k => $v ) {
                $data_keys[] = $k;
            }
        }
        
        $final_data = [];
        $final_data['variables'] = $data_keys;
        foreach($temp_array as $key => $value){
            $temp_data_row = [];
            foreach ( $data_keys as $k => $v ) {    
                if(isset($value[$v])){
                    $temp_data_row[] = $value[$v];
                } else{
                    $temp_data_row[] = 0;
                }
            }
            $final_data[$key] = $temp_data_row;
        }
        
        $chart_data = array();

        foreach ($final_data as $row => $columns) {
          foreach ($columns as $row2 => $column2) {
              $chart_data[$row2][$row] = $column2;
          }
        }
		
        //dd($chart_data);
        $response['data'] = $chart_data;
        return ['status'=>'success','records'=>$response];
    }*/

    public function visualById(Request $request){
        $responseArray = [];
        $visual = GV::find($request->id);
        $dataset_id = $visual->dataset_id;
        $columns = json_decode($visual->columns, true);
        $countCharts = '';
        if($columns == null){
            return ['status'=>'error','message'=>'No settings found!'];
        }
        if(array_key_exists('count', $columns) && is_array(@$columns['count'])){
            $countCharts = $columns['count'];
        }
        $responseArray['num_of_charts'] = count($columns['column_one']);
        $chartsArray = [];
        $datatableName = DL::find($dataset_id);
        if($request->type == 'filter'){
        	$datasetData = DB::table($datatableName->dataset_table)->where('id','!=',1)->where(json_decode($request->filter_array,true))->get()->toArray();
        }else{
        	$datasetData = DB::table($datatableName->dataset_table)->where('id','!=',1)->get()->toArray();
        }

        $dataProce = [];
        foreach($datasetData as $colKey => $value){
           
            $dataProce[] = (array)$value;
        
        }
        $datasetData = $dataProce;
        $datasetColumns = (array)DB::table($datatableName->dataset_table)->where('id',1)->first();
        foreach($columns['column_one'] as $key => $value){
            $columnData = [];
            if(@in_array($key,$countCharts)){//Chart name chart_1 exist in count array
                $tempArray = [];
                foreach($columns['columns_two'][$key] as $colKey => $colVal){
                    $resultData[$colVal] = $this->generateCountColumns($colVal,$datatableName->dataset_table,$request->type == 'filter'?true:false,json_decode($request->filter_array,true));
                }
                $resultCorrectData = $this->correctDataforCount($resultData,$datasetColumns);
                $columnData = $resultCorrectData;
            }else{
                $arrayData = array_column($datasetData, $value);
                array_unshift($arrayData,$datasetColumns[$value]);
                $columnData[] = $arrayData;
                foreach($columns['columns_two'][$key] as $colKey => $colVal){
                    $arrayData = array_column($datasetData, $colVal);
                    array_unshift($arrayData,$datasetColumns[$colVal]);
                    $arrayData = array_merge(array($arrayData[0]),array_map('intval', array_slice($arrayData, 1)));
                    $columnData[] = $arrayData;
                }
            }
            $chartsArray[$key] = $columnData;
        }

        if(!empty(json_decode($visual->filter_columns))){
            $filtersArray = $this->getFIlters($datatableName->dataset_table, json_decode($visual->filter_columns, true), $datasetColumns);
        }else{
            $filtersArray = [];
        }
        $transposeArray = [];
        foreach($chartsArray as $tKey => $tValue){
            $transposeArray[$tKey] = $this->transpose($tValue);
        }
        $globalVisualSettings = GS::where('meta_key','visual_setting')->first();
        $responseArray['chart_data'] = $transposeArray;
        $responseArray['filters'] = $filtersArray;
        $responseArray['chart_types'] = $visual->chart_type;
        $responseArray['default_settings'] = $globalVisualSettings->meta_value;
        $responseArray['settings'] = $columns['visual_settings'];
        $responseArray['titles'] = $columns['title'];
        $responseArray['status'] = 'success';
        return $responseArray;
    }

    protected function transpose($array) {
        array_unshift($array, null);
        return call_user_func_array('array_map', $array);
    }

    protected function getFIlters($table, $columns, $columnNames){
        $columnsWithType = $columns;
        $columns = (array)$columns;
        $columns = array_column($columns, 'column');
        $resultArray = [];
        $model = DB::table($table)->select($columns)->where('id','!=',1)->get()->toArray();
        $tmpAry = [];
        foreach($model as $k => $v){
            $tmpAry[] = (array)$v;
        }

        dd($tmpAry);
        die;
        $index = 1;
        foreach($columns as $key => $value){
            $filter['column_name'] = $columnNames[$value];
            
           // array_column($tmpAry, $value)


            $filter['column_data'] = array_unique(array_column($tmpAry, $value));
            $filter['column_type'] = $columnsWithType['filter_'.$index]['type'];
            $index++;
            $data[$value] = $filter;
        }
        return $data;
    }

    protected function generateCountColumns($column, $table, $filters = false, $filterArray){

        if($filters == true){
            $result = DB::table($table)->select([DB::raw('COUNT(id) as count'),$column])->where($filterArray)->groupBy($column)->get()->toArray();
        }else{
            $result = DB::table($table)->select([DB::raw('COUNT(id) as count'),$column])->groupBy($column)->get()->toArray();
        }
        return $result;
    }

    protected function correctDataforCount($dataForProcess,$datasetColumns){
        
        $dataProce = [];
        foreach($dataForProcess as $colKey => $value){
            $temp = [];
            foreach($value as $k => $v){
                $temp[] = (array)$v;
            }
            $dataProce[$colKey] = $temp;
        }
        $dataForProcess = $dataProce;
        $columns = [];
        $tempArray = [];
        foreach($dataForProcess as $ky => $vl){
            $tempArray[] = array_column($vl, $ky);
        }
        $columnOne = call_user_func_array('array_merge',$tempArray);
        $addColumn = $columnOne;
        array_unshift($addColumn, 'String');
        $columns[] = $addColumn;

        foreach($dataForProcess as $k => $v){
            $tempArray = [];
            foreach($columnOne as $key => $value){
                $key = array_search($value, array_column($v, $k));
                if($key == false){
                    $tempArray[] = 0;
                }else{
                    $tempArray[] = $v[$key]['count'];
                }
            }
            array_unshift($tempArray, $datasetColumns[$k]);
            $columns[] = $tempArray;
        }
        return $columns;
    }

    public function getColumnByDataset($id){

        $model = DL::find($id);
        $datasetTable = $model->dataset_table;
        $model = DB::table($datasetTable)->first();
        unset($model->id);
        return ['status'=>'success','columns'=>$model];
    }

    public function getVisualDetails($id){

        $model = GV::find($id);
        $vSettings = GS::where('meta_key','visual_setting')->first();
        $settings = json_decode($model->columns);
        $returnArray['visual_name'] = $model->visual_name;
        $returnArray['dataset_id'] = $model->dataset_id;
        $returnArray['columns'] = $model->columns;
        $returnArray['filter_columns'] = $model->filter_columns;
        $returnArray['visual_settings'] = @$settings->visual_settings;
        $returnArray['chart_types'] = $model->chart_type;
        $returnArray['visual_set'] = $vSettings;
        return ['status'=>'success','data'=>$returnArray];
    }
    public function saveVisualData(Request $request){

        $validate = $this->validateRequest($request);
        if($validate['status'] == 'false'){
            return ['status'=>'error','message'=>$validate['message']];
        }

        
        $columns = json_decode($request->columns, true);
        $count = json_decode($request->count, true);
        unset($columns['count']);
        
        $newCount = [];
        foreach($count as $key => $value){
            if($value == 'Yes'){
                $newCount[] = $key;
            }
        }
        $columns['count'] = $newCount;
        $filterCOlumns = json_decode($request->filter_cols, true);
        
        
        $model = GV::find($request->visual_id);
        $model->visual_name = $request->visual_name;
        $model->dataset_id = $request->dataset_id;
        $model->columns = json_encode($columns);
        $model->filter_columns = $request->filter_cols;
        $model->chart_type = $request->chartTypes;
        $model->created_by = Auth::user()->id;
        $model->save();
        return ['status'=>'success','message'=>'Visual update successfully!'];
    }

    public function validateRequest($request){

        if($request->has('dataset_id') && $request->has('visual_name') && $request->has('columns') && $request->has('filter_cols') && $request->has('visual_id')){
            if($request->dataset_id != 'undefined' || $request->visual_name != 'undefined' || $request->columns != 'undefined' || $request->filter_cols != 'undefined' || $request->visual_id != 'undefined'){
                $return = ['status'=>'true','message'=>''];
                return $return;
            }else{
                $return = ['status'=>'false','message'=>'Required fields are missing!'];
                return $return;
            }
        }else{
            $return = ['status'=>'false','message'=>'Required fields are missing!'];
            return $return;
        }
    }

    public function calculateVisuals($id){
        
        $model = GV::find($id);
        $dataset_id = $model->dataset_id;
        $datasetTable = DL::find($dataset_id)->dataset_table;
        $filterColumns = json_decode($model->filter_columns);
        $columnsUniqueData = [];
        foreach($filterColumns as $colKey => $column){

            $result = DB::table($datasetTable)->select($column)->where('id','!=',1)->groupBy($column)->get()->toArray();
            $columnsUniqueData[$column] = $result;
        }
        $correctArray = [];
        foreach ($columnsUniqueData as $key => $value) {
            foreach($value as $ikey => $ival){
                $correctArray[$key][] = $ival->{$key};
            }
        }
        function permutations(array $array, $inb = false){

            switch (count($array)){
                case 1:
                    return reset($array);
                    break;
                case 0:
                    throw new InvalidArgumentException('Requires at least one array');
                    break;
            }
            $keys = array_keys($array);
            $a = array_shift($array);
            $k = array_shift($keys);
            $b = permutations($array, 'recursing');
            $return = array();
            foreach ($a as $nk => $v) {
                if($v){
                    foreach ($b as $bk => $v2) {
                        if($inb == 'recursing')
                            $return[] = array_merge(array($v), (array) $v2);
                        else
                            $return[] = array($k => $v) + array_combine($keys, (array)$v2);
                    }
                }
            }
            return $return;
        }
        if(count($correctArray) != 1){
            $combinations = permutations($correctArray);
        }else{
            $correct = [];
            foreach($correctArray as $k => $v){
                foreach($v as $ik => $iv){
                    $correct[][$k] = $iv;
                }
            }
            $combinations = $correct;
        }
        $columns = json_decode($model->columns);
        foreach($combinations as $comKey => $comVal){
            $resultArray = [];
            foreach($columns as $key => $column){
                $index = 1;
                $model = DB::table($datasetTable)->select([DB::raw('COUNT(id) as count'),$column])->where($comVal)->where('id','!=',1)->groupBy($column)->get();
                foreach($model as $mKey => $mVal){
                    $resultArray[$column][$index] = $mVal;
                    $index++;
                }
            }

            $checkExistence = GVQ::where([]);
            echo json_encode($comVal);
            dd();
            /*echo json_encode($resultArray);
            exit;*/
            dump($comVal);
            dump($resultArray);
        }
    }

    public function saveVisualSettings(Request $request){

        $model = GV::find($request->visual_id);
        $modelColumns = json_decode($model->columns,true);
        $modelColumns['visual_settings'][$request->chart][0] = $request->settings;
        $model->columns = json_encode($modelColumns);
        $model->save();
        return ['status'=>'success','message'=>'Settings saved successfully!'];
    }

    public function getVisualsFromDatsetID($dataset_id){

        $model = GV::select(['visual_name','id'])->where('dataset_id',$dataset_id)->get();

        $responseArray = [];
        $index = 0;
        foreach($model as $key => $value){
            $responseArray[$index]['id'] = $value->id;
            $responseArray[$index]['visual_name'] = $value->visual_name;
            $index++;
        }

        return ['status'=>'success','list_visuals'=>$responseArray];
    }
}
