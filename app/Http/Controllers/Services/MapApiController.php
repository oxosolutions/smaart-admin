<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Map;

class MapApiController extends Controller
{
    public function mapList()
    {	
    	try{
    	 	$data  = Map::select(['id','title','code','parent','code_albha_2','code_albha_3'])->get();
    	 	return ['status'=>'success','response'=>$data];
    	 }catch(\Exception $e)
    	 {
    	 	return ['status'=>"error", 'message'=>"Something goes wrong try again"];
    	 }
    }
    public function singleMap($id)
    {
    		$data = Map::where('id',$id)->first();
    		if($data==null || empty($data) )
    		{
    	 		return ['status'=>"error", 'message'=>"Something goes wrong try again"];

    		}else{
				return ['status'=>"success", 'response'=>$data];
			}
    }
    
}
