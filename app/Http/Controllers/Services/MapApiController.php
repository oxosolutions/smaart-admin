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
    	 	$data  = Map::select(['id','title','code','parent','code_albha_2','code_albha_3','description'])->get();
    	 	return ['status'=>'success','response'=>$data];
    	 }catch(\Exception $e)
    	 {
    	 	return ['status'=>"error", 'message'=>"Something goes wrong try again"];
    	 }
    }

    public function createMap(Request $request){
    	try{
    		$model = new Map;
    		$model->title = $request->mapTitle;
    		$model->code = $request->code;
    		$model->code_albha_2 = $request->codeAlpha2;
    		$model->code_albha_3 = $request->codeAlpha3;
    		$model->code_numeric = $request->codeNumeric;
    		$model->parent = $request->parentMap;
    		$model->description = $request->mapDescription;
    		$model->map_data = $request->mapData;
    		$model->status = 'enable';
    		$model->save();
    		return ['status'=>'success','message'=>'Map saved successfully!'];
    	}catch(\Exception $e){
    		throw $e;
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

    public function deleteMap($id){
    	try{
    		$model = Map::find($id);
    		$model->delete();
    		return ['status'=>'success','message'=>'Successfully deleted!'];
    	}catch(\Exception $e){
    		throw $e;
    	}
    }

    public function updateMap(Request $request){
    	try{
    		$model = Map::find($request->map_id);
    		$model->title = $request->mapTitle;
    		$model->code = $request->code;
    		$model->code_albha_2 = $request->codeAlpha2;
    		$model->code_albha_3 = $request->codeAlpha3;
    		$model->code_numeric = $request->codeNumeric;
    		$model->parent = $request->parentMap;
    		$model->description = $request->mapDescription;
    		$model->map_data = $request->mapData;
    		$model->status = 'enable';
    		$model->save();
    		return ['status'=>'success','message'=>'Map saved successfully!'];
    	}catch(\Exception $e){
    		throw $e;
    	}
    }
    
}
