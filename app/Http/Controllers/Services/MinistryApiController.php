<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ministrie as MIN;
class MinistryApiController extends Controller
{
    public function Ministries()
    {
        $model = MIN::WithUsers()->get();

        $responseArray = [];
        $index = 0;
        foreach($model as $key => $ministry){

            $responseArray[$index]['id'] = $ministry->id;
            $responseArray[$index]['ministry_id'] = $ministry->ministry_id;
            $responseArray[$index]['ministry_title'] = $ministry->ministry_title;
            $responseArray[$index]['ministry_description'] = $ministry->ministry_description;
            $responseArray[$index]['ministry_icon'] = $ministry->ministry_icon;
            $responseArray[$index]['ministry_image'] = $ministry->ministry_image;
            $responseArray[$index]['ministry_phone'] = $ministry->ministry_phone;
            $responseArray[$index]['ministry_ministers'] = $ministry->ministry_ministers;
            $inIndex = 0;
            foreach($ministry->departments as $ky => $vl){

                $responseArray[$index]['departments'][$inIndex]['dep_code'] = $vl->department->dep_code;
                $responseArray[$index]['departments'][$inIndex]['dep_name'] = $vl->department->dep_name;
                $inIndex++;
            }
            $responseArray[$index]['ministry_order'] = $ministry->ministry_order;
            $responseArray[$index]['created_by'] = $ministry->created_by;
            $responseArray[$index]['created_at'] = $ministry->created_at->format('Y-m-d H:i:s');
            $index++;
        }

        return ['status' => 'success' , 'records' => $responseArray];
    }
    public function ministryList(){

    	$model = MIN::WithUsers()->get();

    	$responseArray = [];
    	$index = 0;
    	foreach($model as $key => $ministry){

            $responseArray[$index]['id'] = $ministry->id;
    		$responseArray[$index]['ministry_id'] = $ministry->ministry_id;
    		$responseArray[$index]['ministry_title'] = $ministry->ministry_title;
    		$responseArray[$index]['ministry_description'] = $ministry->ministry_description;
    		$responseArray[$index]['ministry_icon'] = $ministry->ministry_icon;
    		$responseArray[$index]['ministry_image'] = $ministry->ministry_image;
    		$responseArray[$index]['ministry_phone'] = $ministry->ministry_phone;
    		$responseArray[$index]['ministry_ministers'] = $ministry->ministry_ministers;
    		$inIndex = 0;
    		foreach($ministry->departments as $ky => $vl){

    			$responseArray[$index]['departments'][$inIndex]['dep_code'] = $vl->department->dep_code;
    			$responseArray[$index]['departments'][$inIndex]['dep_name'] = $vl->department->dep_name;
    			$inIndex++;
    		}
    		$responseArray[$index]['ministry_order'] = $ministry->ministry_order;
    		$responseArray[$index]['created_by'] = $ministry->created_by;
    		$responseArray[$index]['created_at'] = $ministry->created_at->format('Y-m-d H:i:s');
    		$index++;
    	}

    	return $responseArray;
    }

    public function singleMinistry($id){

        $model = MIN::WithUsers()->findOrFail($id);
        if ($model == "" || empty($model)){
            return ['status' => 'error','message'=>'no file found'];
        }else{
            $responseArray = [];
            $index = 0;

            $responseArray[$index]['id'] = $model->id;
            $responseArray[$index]['ministry_id'] = $model->ministry_id;
            $responseArray[$index]['ministry_title'] = $model->ministry_title;
            $responseArray[$index]['ministry_description'] = $model->ministry_description;
            $responseArray[$index]['ministry_icon'] = $model->ministry_icon;
            $responseArray[$index]['ministry_image'] = $model->ministry_image;
            $responseArray[$index]['ministry_phone'] = $model->ministry_phone;
            $responseArray[$index]['ministry_ministers'] = $model->ministry_ministers;
            $inIndex = 0;
            foreach($model->departments as $ky => $vl){

                $responseArray[$index]['departments'][$inIndex]['dep_code'] = $vl->department->dep_code;
                $responseArray[$index]['departments'][$inIndex]['dep_name'] = $vl->department->dep_name;
                $inIndex++;
            }
            $responseArray[$index]['ministry_order'] = $model->ministry_order;
            $responseArray[$index]['created_by'] = $model->created_by;
            $responseArray[$index]['created_at'] = $model->created_at->format('Y-m-d H:i:s');
            $index++;

            return $responseArray;  
        }
       
    }
}
