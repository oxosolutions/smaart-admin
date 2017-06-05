<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department as DM;
class DepartmentApiController extends Controller
{
    public function departmentList(){

        $model = DM::WithUsers()->get();

        $responseArray = [];
        $index = 0;
        foreach($model as $key => $value){
            $responseArray[$index]['id'] = $value->id;
            $responseArray[$index]['dep_code'] = $value->dep_code;
            $responseArray[$index]['dep_name'] = $value->dep_name;
            $responseArray[$index]['created_by'] = $value->created_by;
            $responseArray[$index]['created_at'] = $value->created_at->format('Y-m-d H:i:s');
            $index++;
        }
        return $responseArray;
    }
    public function departments(){

    	$model = DM::WithUsers()->get();

    	$responseArray = [];
    	$index = 0;
    	foreach($model as $key => $value){
    		$responseArray[$index]['id'] = $value->id;
    		$responseArray[$index]['dep_code'] = $value->dep_code;
    		$responseArray[$index]['dep_name'] = $value->dep_name;
    		$responseArray[$index]['created_by'] = $value->created_by;
    		$responseArray[$index]['created_at'] = $value->created_at->format('Y-m-d H:i:s');
    		$index++;
    	}
    	
        return ['status' => 'success' , 'records' => $responseArray];
    }

    public function singleDepartment($id){

        $model = DM::WithUsers()->findOrFail($id);
        $responseArray = [];
        $index = 0;

        $responseArray[$index]['id'] = $model->id;
        $responseArray[$index]['dep_code'] = $model->dep_code;
        $responseArray[$index]['dep_name'] = $model->dep_name;
        $responseArray[$index]['created_by'] = $model->created_by;
        $responseArray[$index]['created_at'] = $model->created_at->format('Y-m-d H:i:s');
        $index++;
        
        return $responseArray;
    }
}
