<?php

namespace App\Http\Controllers\Services;

use App\Designation as DS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignationApiController extends Controller
{
   public function DesignitionList()
   {
   		$data = DS::all();
   		if(count($data) < 1){
   		 	return ['status' => 'error' ,'records' => 'no record found'];
   		}else{
   			return ['status' => 'success' , 'records' => $data];
   		}
	}
}