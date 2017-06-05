<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneratedVisual as GV;
use App\DatasetsList as DL;
use DB;
use App\GeneratedVisualQuerie as GVQ;
class VisualApiController extends Controller
{
    public function visualList(){

    	$model = GV::all();
    	$resultArray = [];
    	foreach($model as $key => $value){
    		$tempArray = [];
    		$tempArray['id'] = $value->id;
    		$tempArray['visual_name'] = $value->visual_name;
    		$tempArray['dataset'] = array('dataset_id'=>$value->dataset_id,'dataset_name'=>$value->datasetName->dataset_name);
    		$datasetData = DL::find($value->dataset_id);
    		$dataTableData = DB::table($datasetData->dataset_table)->select(json_decode($value->columns))->where('id',1)->first();
    		$columnArray = [];
    		foreach(json_decode($value->columns) as $colKey => $colValue){
    			$columnArray[$colValue] = $dataTableData->{$colValue};
    		}
    		$tempArray['columns'] = $columnArray;
    		$tempArray['gen_columns'] = json_decode($value->query_result);
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

    public function visualById($id){
        $model = GV::find($id);
        $tempArray = [];
        $tempArray['id'] = $model->id;
        $ColumnsCount = json_decode($model->filter_counts);
       
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
		/*
		echo "<br>......................";
		echo "<pre>";
		print_r($final_data);
		echo "</pre>";
		
		echo "<br>......................";
		echo "<pre>";
		print_r($chart_data);
		echo "</pre>";
		
		*/

		
        //dd($chart_data);
        $response['data'] = $chart_data;
        return ['status'=>'success','records'=>$response];
    }

}
