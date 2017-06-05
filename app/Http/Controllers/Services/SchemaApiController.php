<?php

namespace App\Http\Controllers\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GoalsSchema as GS;

class SchemaApiController extends Controller
{
	public function allSchema(){
		$model = GS::WithUsers()->get();
		$decode_model = json_decode($model);

		if (!empty($decode_model) || count($decode_model) < 0 || $decode_model == ""){
			$responseArray = [];
	    	$index = 0;
	    	
	    	foreach($model as $key => $value){

	    		$responseArray[$index]['schema_id'] = $value->schema_id;
	    		$responseArray[$index]['schema_title'] = $value->schema_title;
	    		$responseArray[$index]['schema_image'] = $value->schema_image;
	    		$responseArray[$index]['schema_desc'] = $value->schema_desc;
	    		$responseArray[$index]['created_by'] = $value->created_by;
	    		$index++;
	    	}

	    	return ['status'=>'success','records'=>$responseArray];
		}else{
			return ['status'=>'fail','records'=>'There is a problem in the result or no Schema found.'];
		}
		
	}    
}
