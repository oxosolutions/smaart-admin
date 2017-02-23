<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Surrvey;
use App\SurrveyQuestion as SQ;
use Auth;
use App\UserMeta as um;
use DB;

class SurrveyApiController extends Controller
{

	

	public function surrvey_save(Request $request)
    {
    	 	

    	try{
			$user_id =	Auth::user()->id;
    	    $org = um::select('value')->where(['user_id'=>$user_id,'key'=>'organization'])->first(); 
			$org_id = $org->value;
            $surrvey = new Surrvey();
            $surrvey->name = $request->name;
            $surrvey->description = $request->description;
            $surrvey->created_by = Auth::user()->id;
            $surrvey->save();
            	return ['status'=>'success', 'response'=>'successfully created surrvey'];
            }catch(\Exception $e)
            {
                return ['status'=>'error', 'response'=>'Something goes wrong Try Again'];

            }   
    		
    }
    public function surrvey_list()
    {
           $model = Surrvey::select(['id','name','description','status','created_by'])->orderBy('id','desc')->get();
           return ['status'=>"success", "response"=>$model];
    }

    public function enableDisable($id)
	{
		try
		{
			$status = Surrvey::select('status')->where('id',$id)->first();
			if($status->status=="enable"){
				Surrvey::select('status')->where('id',$id)->update(['status'=>'disable']);
			}else if($status->status=="disable")
			{
				Surrvey::select('status')->where('id',$id)->update(['status'=>'enable']);

			 }
			return ['status'=>'success', 'message'=>"successfully change status"];

		}catch(\Exception $e)
		{
			return ['status'=>'error', 'message'=>"Something goes wrong Try Again"];

		}

	}

	public function delSurrvey($id)
	{
		try{
			Surrvey::where('id',$id)->delete();
			return ['status'=>'success', 'message'=>"successfully Delete Surrvey"];

		}catch(\Exception $e)
		{
			return ['status'=>'error', 'message'=>"Something goes wrong Try Again"];
		}

	}

	public function surrvey_edit($id)
    	{
        try{
            Surrvey::findORfail($id);
            $model =  Surrvey::select(['id','name','description','status','created_by'])->where('id',$id)->first();
            	return['status'=>'success' ,'response'=>$model];  
            }catch(\Exception $e)
            {
           		 return['status'=>'error' ,'response'=>"Something goes wrong Try Again"];  
            }    
    	}
	public function surrvey_update(Request $request )
	    {
	        try{
	            Surrvey::findORfail($request->id);
	            Surrvey::where('id',$request->id)->update(['name'=>$request->name, 'description'=>$request->description,'status'=>$request->status]);
            	return ['status'=>'success', 'response'=>'successfully created surrvey'];
	        }catch(\Exception $e)
	        {
            	return ['status'=>'success', 'response'=>'Something goes wrong Try Again'];
	        }
	    }
    public function surrveyData($surrvey_id)
    {
    		$model = Surrvey::where('id',$surrvey_id)->first();
	    	$array['surrvey_id'] = 	$model->id;
		    $array['surrvey_name'] = 	$model->name;
		    $array['description'] =  $model->description;
			$array['created_by'] =  $model->created_by;
			$array['enable_status'] = $model->status;
			if($model->authentication_required   !=null){
				$array['authentication_required'] = $model->authentication_required;
			}
			if($model->authentication_type   !=null){
				$array['authentication_type'] = $model->authentication_type;
			}
			if($model->authorize   !=null){
				$array['authorize'] = json_decode($model->authorize,true);
			}
			if($model->scheduling   !=null){
				$array['scheduling'] = $model->scheduling;
			}
			if($model->start_date   !=null){
				$array['start_date'] = $model->start_date;
			}
			if($model->expire_date   !=null){
				$array['expire_date'] = $model->expire_date;
			}
			if($model->timer_status   !=null){
				$array['timer_status'] = $model->timer_status;
			}
			if($model->timer_type   !=null){
				$array['timer_type'] = $model->timer_type;
			}
			if($model->timer_durnation   !=null){
				$array['timer_durnation'] = $model->timer_durnation;
			}
			if($model->response_limit_status   !=null){
				$array['response_limit_status'] = $model->response_limit_status;
			}
			if($model->response_limit   !=null){
				$array['response_limit'] = $model->response_limit;
			}
			if($model->response_limit_type   !=null){
				$array['response_limit_type'] = $model->response_limit_type;
			}
			if($model->error_messages   !=null){
				$array['error_messages'] = $model->error_messages;
			}
			if($model->error_message_value   !=null){
				$array['error_message_value'] = json_decode($model->error_message_value,true);
			}

			
		foreach ($model->questions as $key => $value) {
			$ans = json_decode($value->answer,true);
			if(isset($ans["question_key"]))
			{
				$array['questionData'][$key]["question_key"]= $ans["question_key"];
			}
			$array['questionData'][$key]["question_id"] = $value->id;
			if(isset($ans["question_order"]))
			{
				$array['questionData'][$key]["question_order"]	=	$ans["question_order"];
			}
			$array['questionData'][$key]["question_text"] = $value->question;
			if(isset($ans["question_desc"]))
			{
				$array['questionData'][$key]["question_desc"]	=	$ans["question_desc"];
			}
    		$array['questionData'][$key]["question_type"]= $ans["type"];

    		if(isset($ans["media"]))
			{
				$array['questionData'][$key]["media"]	=	$ans["media"];
			}
			if(isset($ans["pattern"]))
			{
				$array['questionData'][$key]["pattern"]	=	$ans["pattern"];
			}
			if(isset($ans["validation"]))
			{
				$array['questionData'][$key]["validation"]	=	$ans["validation"];
			}

			if(isset($ans["slug"]))
			{
				$array['questionData'][$key]["question_slug"]=	$ans["slug"];
			}
			if(isset($ans["instruction"]))
			{
				$array['questionData'][$key]["question_instruction"]	=	$ans["instruction"];
			}
			if(isset($ans["format"]))
			{
				$array['questionData'][$key]["question_format"]	=	$ans["format"];
			}
			if(isset($ans["message"]))
			{
				$array['questionData'][$key]["question_message"]	=	$ans["message"];
			}
			if(isset($ans["conditional_logic"]))
			{
				$array['questionData'][$key]["conditions"]	=	$ans["conditional_logic"];
			}
			if(isset($ans["placeholder"]))
			{
				$array['questionData'][$key]["placeholder"]	=	$ans["placeholder"];
			}
			if(isset($ans["required"]))
			{
				$array['questionData'][$key]["required"]	=	$ans["required"];
			}
			if(isset($ans["minimum"]))
			{
				$array['questionData'][$key]["minimum"]	=	$ans["minimum"];
			}
			if(isset($ans["maximum"]))
			{
				$array['questionData'][$key]["maximum"]	=	$ans["maximum"];
			}									
			if(isset($ans["extra_options"]))
			{
				$array['questionData'][$key]["extra_options"]	=	$ans["extra_options"];
			}
		if(isset($ans["option"]))
			{
				foreach ($ans["option"]["value"] as $okey => $value) {
				$array['questionData'][$key]["answers"][$okey]["option_type"] = $ans["type"];
				$array['questionData'][$key]["answers"][$okey]["option_text"] =	$ans["option"]["key"][$okey];
				$array['questionData'][$key]["answers"][$okey]["option_next"] =	$ans["option"]["option_next"][$okey];
				$array['questionData'][$key]["answers"][$okey]["option_status"] =	$ans["option"]["option_status"][$okey];
				$array['questionData'][$key]["answers"][$okey]["option_prompt"] =	$ans["option"]["option_prompt"][$okey];
				$array['questionData'][$key]["answers"][$okey]["option_value"]= $value;
				}
			}
    		//$array['questionData'][$key]["answer"][]	 = json_decode($value->answer,true);
		}
		return ['response'=>$array];    		
    }
}
