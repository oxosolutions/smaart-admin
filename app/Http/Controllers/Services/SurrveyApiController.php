<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Surrvey;
use App\SurveyQuestion as SQ;
use Auth;
use App\UserMeta as um;
use DB;
use App\SurveyQuestionGroup as GROUP;
use App\SurveySetting as SSETTING;
use App\SurveyEmbed as SEMBED;
use Illuminate\Support\Facades\Schema;
use App\organization as org;
use Session;
use App\DatasetsList;
use SurveyHelper;
use Carbon\Carbon;
use App\GlobalSetting as GS;

class SurrveyApiController extends Controller
{

	public function save_survey_filled_data(Request $request)
	{

		$org_id = org::select('id')->where('activation_code' ,$request->activation_code)->first()->id;
		Session::put('org_id', $org_id);
		$data = json_decode($request->export,true);
		
		$surveyid = $data[0]["surveyid"];
		$table = $org_id.'_survey_data_'.$surveyid;
		if(!Schema::hasTable($table))
    	{
    		SurveyHelper::create_survey_table($surveyid , $org_id);
		}
		else{
			SurveyHelper::alter_survey_table($surveyid , $org_id);
		}
		foreach ($data as $key => $value) {
			$survey_check =0;
			if($value['status']=="completed")
			{
				$status =1;	
			}else{
				$status =0;
			}
			$insert["survey_status"] = $status;
			$insert["survey_started_on"] = 	$value['starton'];
			$insert["survey_completed_on"] = 	$value['endon']; 
			$insert["ip_address"] = $request->ip();
			$insert["survey_submitted_from"] = "APP";
			if(isset($value['unique_id']))
			{
				$insert["unique_id"] = 	@$value['unique_id'];
			$survey_check = DB::table($table)->where('unique_id',$insert["unique_id"])->count();
			//dd($surve_check);
			}
			dump($value);
			foreach ($value['answers'] as $ansKey => $ansValue) {	
				if(isset($ansValue["answer"]))
				{
					if(is_array($ansValue["answer"]))
					{
					$insert[$ansValue["questkey"]] = json_encode($ansValue["answer"]);
					}
					else
					{
						$insert[$ansValue["questkey"]] = $ansValue["answer"];
					}
				}				 
			}
			if($survey_check==0)
			{		
				DB::table($table)->insert($insert);	
			}	
		}		
	}
//VIEW SURVEY SAVED DATA 
	public function view_survey_saved_data($sid)
	{
		try{
			Surrvey::findORfail($sid);
			$table = Surrvey::select('survey_table')->where('id',$sid)->first()->survey_table;
			$data = DB::table($table)->get();
			foreach ($data as $key => $value) {
				foreach ($value as $aKey => $ansValue) {
					if(is_array(json_decode($ansValue,true)))
					{
						$data[$key]->$aKey = implode(', ', json_decode($ansValue,true));
					}
				}
			}
			return ['status'=>'success', 'data'=> $data];

		}catch(\Exception $e)
		{
			throw $e;			
			return ['status'=>'error', 'message'=>'something goes wrong try again'];	
		}
	} 
	//embeds  surrvey
	public function survey_embeds(Request $request)
	{
		$token  = str_random(15);
		$user = Auth::user();
		$count = SEMBED::where(['user_id'=>$user->id, 'survey_id'=>$request->survey_id])->first();
		if($count==null){
			$sembed = new SEMBED();
			$sembed->user_id = $user->id;
			$sembed->org_id = $user->organization_id;
			$sembed->survey_id =  $request->survey_id;
			$sembed->embed_token = $token; 
			$sembed->save();	
			return ['status'=>'success', 'token'=>$token, 'message'=>'Successfully save survey embed'];
		}else{
			return ['status'=>'error', 'message'=>'already created','token'=>$count->embed_token];
		}
	}

	public function save_survey_groups(Request $request){
		try{

			DB::beginTransaction();
			$survey_id 	= $request['survey_id'];
			$grp 		= GROUP::where('survey_id',$survey_id);
			$data 		= json_decode($request['survey_data'],true);
			$sorts = explode(',',$request->sorts);
			$sortIndex = 1;
			$groupIds = [];
			foreach($data as $key => $value) {
				$groupExistOrNot = GROUP::find($value['group_id']);
				if($groupExistOrNot != null){
					$groupExistOrNot->survey_id 	=	$survey_id;
					$groupExistOrNot->title 		=	$value["group_name"]; 
					$groupExistOrNot->description 	=	$value["group_description"];
					$groupExistOrNot->group_order 	= 	$sortIndex;
					$groupExistOrNot->save();
					$groupIds[] = $groupExistOrNot->id;
				}else{
					//group 
					$grp = new GROUP();
					$grp->survey_id 	=	$survey_id;
					$grp->title 		=	$value["group_name"]; 
					$grp->description 	=	$value["group_description"];
					$grp->group_order 	=	$sortIndex;
					$grp->save();
					$groupIds[] = $grp->id;
				}
				$sortIndex++;
			}
			GROUP::whereNotIn('id',$groupIds)->where('survey_id',$survey_id)->delete();
			DB::commit();
			return ['status'=>"success", "message"=>'Section updated successfully!'];
		}catch(\Exception $e){
			DB::rollback();
			throw $e;
		}
		
	}

	public function save_survey_question(Request $request){
		try{
				DB::beginTransaction();
				$survey_id 	= $request['survey_id'];
				$grp 		= $request['group_id'];
				$sq 		= SQ::where(['survey_id'=>$survey_id,'group_id'=>$grp]);		
				$data 		= json_decode($request['survey_data'],true);
				if($sq->count() > 0){

					$sq->forceDelete();
				}
				$sortIndex = 1;
				foreach ($data as $key => $val) {
						 
					$sq = 	 new SQ();
					$sq->question = $val["question"];
					unset($val["question"]);
					$sq->answer = 	json_encode($val);
					$sq->survey_id = $survey_id;
					$sq->group_id = $grp;
					$sq->quest_order = $sortIndex;
					$sq->save();
					$sortIndex++;
				}
				DB::commit();
				return ['status'=>"success", "message"=>'Questions saved successfully!'];
		}catch(\Exception $e){
			DB::rollback();
			throw $e;
		}
	}

	//SAVE & EDIT SURVEY GROUP QUESTION
	public function save_survey_data(Request $request)
	{	
		$survey_id 	= $request['survey_id'];
		$grp 		= GROUP::where('survey_id',$survey_id);
		$sq 		= SQ::where('survey_id', $survey_id);		
		$data 		= json_decode($request['survey_data'],true);
		try{
				DB::beginTransaction();
				if($grp->count() > 0)
				{
					$grp->forceDelete();
					$msg = "Update Successfully Survey Group Question";
				}
				else{
						$msg = "Successfully Create Survey Group & Questions";
				}
				if($sq->count() > 0)
				{
					$sq->forceDelete();
				}
				foreach($data as $key => $value) {
					//group 
					$grp = new GROUP();
					$grp->survey_id 	=	$survey_id;//$request['survey_id'];
					$grp->title 		=	$value["group_name"]; 
					$grp->description 	=	$value["group_description"]; 
					$grp->save();
					foreach ($value['group_questions'] as $key => $val) {
					// group Question				 
						$sq = 	 new SQ();
						$sq->question = $val["question"];
						unset($val["question"]);
						$sq->answer = 	json_encode($val);
						$sq->survey_id = $survey_id;
						$sq->group_id = $grp->id;
						$sq->save();
					}
				}
				DB::commit();
				return ['status'=>"success", "message"=>$msg];
			}catch(\Exception $e){
				DB::rollback();
				throw $e;
			}				
	}

	public function surveyFields($survey_id, $group_id){
		try{
			$model = SQ::where(['survey_id'=>$survey_id,'group_id'=>$group_id])->orderBy('quest_order')->get();
			$surveyName = Surrvey::find($survey_id);
			$groupName = GROUP::find($group_id);
			$survey = [];
			if(!$model->isEmpty()){
				$index = 1;
				foreach($model as $key => $question){
					$answer = json_decode($question->answer,true);
					foreach ($answer as $anskey => $ansVal) {
						$survey['group'][1]['group_questions'][$index][$anskey] =$ansVal;
					}
					$survey['group'][1]['group_questions'][$index]['survey_id'] = $question->survey_id;
					$survey['group'][1]['group_questions'][$index]['question']  = $question->question;
		        	$survey['group'][1]['group_questions'][$index]['group_id']  = $question->group_id;
					$index++;
				}
				return ['status'=>'success','data'=>$survey,'survey_name'=>$surveyName->name,'group_name'=>$groupName->title];
			}else{
				return ['status'=>'success','data'=>[],'message'=>'No questions found!','survey_name'=>$surveyName->name,'group_name'=>$groupName->title];
			}
		}catch(\Exception $e){
			throw $e;
		}
	}

// DRAW SURVEY 

	// FOR VIEW GROUP & QUESTION 
	public function view_survey_data($id)
	{
		try{
			// Surrvey::findORfail($id);
			$sdata = Surrvey::with(['group'=>function($query){
				$query->orderBy('group_order');
			}])->find($id);	
			$survey['id'] 			= 	$sdata->id;
			$survey['survey_name'] 	= 	$sdata->name;
	  		$survey['created_by']	= 	$sdata->created_by;
	  		//$survey['user_name']	= 	$sdata->creat_by->name;
	  		$survey['description'] 	=	$sdata->description;
	  		$survey['status'] 		= 	$sdata->status;
	  		if(count($sdata->group)>0){
				foreach ($sdata->group as $key => $grp) {
					$survey['group'][$key]['group_id'] 			= $grp->id;
					$survey['group'][$key]['survey_id'] 		= $grp->survey_id;
					$survey['group'][$key]['group_name'] 		= $grp->title;
		    		$survey['group'][$key]['group_description'] = $grp->description;			
		    		$survey['group'][$key]['group_order'] 		= $grp->group_order;			
					foreach ($grp->question as $qkey => $ques) {
						$answer = json_decode($ques->answer,true);
						foreach ($answer as $anskey => $ansVal) {
							$survey['group'][$key]['group_questions'][$qkey][$anskey] =$ansVal;
						}
						$survey['group'][$key]['group_questions'][$qkey]['survey_id']  	= $ques->survey_id;
		        		$survey['group'][$key]['group_questions'][$qkey]['question']  	= $ques->question;
		        		$survey['group'][$key]['group_questions'][$qkey]['group_id']  	= $ques->group_id;
					}			
				}
			}else{

				$survey['group'] 	=[];
				$survey['question']	=[];
			}

			return ['status'=>'success','response'=>$survey];	
		}catch(\Exception $e)
		{
			throw $e;
			return ['status'=>'error','message'=>"Something goes wrong"];	
		}	
	}
	public function generate_survey($id)
	{
		try{
			Surrvey::findORfail($id);
			$sdata = Surrvey::find($id);	
			$survey['survey_id'] 			= 	$sdata->id;
			$survey['survey_name'] 		= 	$sdata->name;
	  		$survey['survey_author_id']	= 	$sdata->created_by;
	  		$survey['survey_author_name']	= 	$sdata->creat_by->name;
	  		$survey['survey_author_role_id']	= 	$sdata->creat_by->role_id;
	  		$survey['survey_author_role_name']	= 	$sdata->creat_by->roles->name;
	  		$survey['survey_description'] 	=	$sdata->description;
	  		$survey['survey_status'] 		= 	$sdata->status;
	  		if(count($sdata->setting) >0)
	  		{
	  			foreach ($sdata->setting as $key => $value) {	  		
		            $survey['survey_settings'][$value->key]= $value->value;
	            }
	        }
	  		if(count($sdata->group)>0){
				foreach ($sdata->group as $key => $grp) {
					$survey['survey_group'][$key]['group_id'] = $grp->id;
					$survey['survey_group'][$key]['survey_id'] = $grp->survey_id;
					$survey['survey_group'][$key]['title'] = $grp->title;
		    		$survey['survey_group'][$key]['description'] =$grp->description;			
					foreach ($grp->question as $qkey => $ques) {
						$answer = json_decode($ques->answer,true);
						foreach ($answer as $anskey => $ansVal) {
							$survey['survey_group'][$key]['question'][$qkey][$anskey] =$ansVal;
						}
						$survey['survey_group'][$key]['question'][$qkey]['survey_id']  = $ques->survey_id;
		        		$survey['survey_group'][$key]['question'][$qkey]['question']  = $ques->question;
		        		$survey['survey_group'][$key]['question'][$qkey]['group_id']  = $ques->group_id;
					}			
				}
			}else{
				$survey['survey_group'] =[];
				$survey['survey_question']=[];
			}
			return ['status'=>'success','response'=>$survey];	
		}catch(\Exception $e)
		{
			throw $e;
			return ['status'=>'error','message'=>"Something goes wrong"];	
		}	
	}



// SAVE SETTING 
	protected function save_survey_setting($data)
    {
		$ssetting = new SSETTING();
		$ssetting->survey_id = $data['survey_id'];
		$ssetting->key 	= $data['key'];
		$ssetting->value = $data['value'];
		$ssetting->save();
    }
// SAVE SURVEY & SETTING
	public function surrvey_save(Request $request)
    {

    	// $ssdata =	json_decode($request->settings,true);
     //    dump($ssdata);
     //    dump($request->all());

     //    die;

		try{
			$user_id =	Auth::user()->id;
			$org_id = Auth::user()->organization_id;
            $surrvey = new Surrvey();
            $surrvey->name = $request->name;
            if ($request->description == "undefined"){
            	 $surrvey->description = " ";
            }else{
            	$surrvey->description = $request->description;
            }
            
            $status = '0';
			if($request->enableDisable=="true")
            {           	
            	 $status ='1';
            }         
            $surrvey->status = $status;
			$surrvey->created_by = Auth::user()->id;
            $surrvey->save();
           	$sid = $surrvey->id;
          	$ssdata =	json_decode($request->settings,true);
          	$this->setting_save($ssdata, $sid);
    	    return ['status'=>'success', 'survey_id'=> $sid, 'response'=>'successfully created surrvey'];
            }catch(\Exception $e)
            {
            	throw $e;
            	
                //return ['status'=>'error', 'response'=>'Something goes wrong Try Again'];

            }   
    		
    }
    // SURVEY LIST
    public function surrvey_list()
    {
           $model = Surrvey::select(['id','name','description','status','created_by'])->orderBy('id','desc')->get();
           return ['status'=>"success", "response"=>$model];
    }
    // SURVEY ENABLE & DISABLE 
    public function enableDisable($id)
	{
		try
		{
			$status = Surrvey::select('status')->where('id',$id)->first();
			if($status->status=="1"){
				Surrvey::select('status')->where('id',$id)->update(['status'=>'0']);
			}else if($status->status=="0")
			{
				Surrvey::select('status')->where('id',$id)->update(['status'=>'1']);

			 }
			return ['status'=>'success', 'message'=>"successfully change status"];

		}catch(\Exception $e)
		{
			return ['status'=>'error', 'message'=>"Something goes wrong Try Again"];

		}

	}
// SURVEY DELETE
	public function delSurrvey($id)
	{
		try{
			DB::beginTransaction();
			Surrvey::where('id',$id)->forceDelete();
			$grp = GROUP::where('survey_id',$id);
			$sq = SQ::where('survey_id', $id);
			if($grp->count() > 0)
			{
				$grp->forceDelete();
			}
			if($sq->count() > 0)
			{
				$sq->forceDelete();
			}

		$setting = 	SSETTING::where('survey_id', $id);
		if($setting->count() > 0)
		{
			$setting->forceDelete();
		}
			DB::commit();
			return ['status'=>'success', 'message'=>"successfully Delete Surrvey"];

		}catch(\Exception $e)
		{
			DB::rollback();
			return ['status'=>'error', 'message'=>"Something goes wrong Try Again"];
		}

	}
//  EDIT SURVEY & SETTING
	public function surrvey_edit($id)
    	{
        try{
            Surrvey::findORfail($id);
            $model = Surrvey::find($id);
            $survey['id'] =	$model->id;
            $survey['name'] =	$model->name;
            $survey['description'] = $model->description;
            $survey['status'] =	$model->status;
            foreach ($model->setting as $key => $value) {
            $survey[$value->key]= $value->value;
            }
         
            	return['status'=>'success' ,'response'=>$survey];  
            }catch(\Exception $e)
            {
            	throw $e;
            	
           		 return['status'=>'error' ,'response'=>"Something goes wrong Try Again"];  
            }    
    	}
    	protected function setting_save($ssdata,$sid )
    	{
    		
    		foreach ($ssdata as $key => $value) {
    			// if($key =="authorized_users")
    			// {
    			// 	if($value ==null)
    			// 	{dump(123);
    			// 		$value = [Auth::user()->id];
    			// 	}
    			// 	else{
    			// 		//dump(count($value));
    			// 		$uid = Auth::user()->id;
    			// 		$value = array_add($value,count($value), $uid );
    			// 	}
    			// 	dump($value);
    			// }

		        if($key == "survey_custom_error_messages_list" )
          		{
          			$value = json_encode($value);
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>$value];
          			$this->save_survey_setting($settingdata);
          		}elseif($key =="authorized_roles" && $value !=null)
          		{
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>json_encode($value)];
          			$this->save_survey_setting($settingdata);

          		}else if($key =="authorized_users" && $value !=null)
          		{
          			//dd()
          			$value[] = Auth::user()->id;


          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>json_encode($value)];
          			$this->save_survey_setting($settingdata);
          		}
          		elseif($key =="survey_start_date" && $value !=null)
          		{
          		$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>date('Y-m-d H:i:s',strtotime($value))];
          		$this->save_survey_setting($settingdata);

          		
          		}elseif($key =="survey_expiry_date" && $value !=null)
          		{
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>date('Y-m-d H:i:s',strtotime($value))];
          			$this->save_survey_setting($settingdata);

          		}
				else{
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>$value];
          			$this->save_survey_setting($settingdata);
	          		}
          		}
    	}
//  UPDATE SURVEY & SETTING  survey_expiry_date
	public function survey_update(Request $request )
	    {

	    		// $ssdata =	json_decode($request->settings,true);
	    		// dump($ssdata);
	    		// die;
	        try{
	        	DB::beginTransaction();
	            Surrvey::findORfail($request->id);
	            	$status = "0";
	            if($request->enableDisable=="true")
	            {
	            	$status = "1";
	            }
	            $array = array(
	            		'name'			=>	$request->name,
	            		'description'	=>	$request->description,
	            		'status'		=>	$status
	            	);
	            Surrvey::where('id',$request->id)->update(['name'=>$request->name, 'description'=>$request->description,'status'=>$status]);
	            $ssetting = SSETTING::where('survey_id',$request->id);
	            	if($ssetting->count() > 0){
	            		$ssetting->forceDelete();
	            	}
	            	$sid = $request->id;
		          	$ssdata =	json_decode($request->settings,true);
		          	$this->setting_save($ssdata, $sid);

		          DB::commit();		
            	return ['status'=>'success', 'response'=>'successfully updated surrvey'];
	        }catch(\Exception $e)
	        {
	        	DB::rollback();
	        	throw $e;
	        	return ['status'=>'success', 'response'=>'Something goes wrong Try Again'];
	        }
	    }
	// OLD API    
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

    
//FILLED SURVEY LIST
    public function answeredSurveysList(){
    	$a =0;
    	$survey_data =	surrvey::select(['id','name','survey_table'])->whereNotNull('survey_table')->get()->toArray();
    	foreach ($survey_data as $key => $value) {
		$table = $value['survey_table'];
		if(Schema::hasTable($table))
		{
			$count = DB::table($table)->count();
			if($count>0)
			{
				$a=1;
				$survey[$key]['id'] = $value['id'];
				$survey[$key]['name'] = $value['name'];
			}
		}
		}
		if($a==0)
		{
			return ['status'=>'error','message'=>'no filled surrvey'];

		}
    	return ['status'=>'successfully','survey_list'=>$survey];
	}

	public function getSurveyThemes(){
		$model = GS::select('meta_value')->where("meta_key","survey_themes")->first();

		return ['status'=>'success','themes'=>json_decode($model->meta_value)];
	}

	public function createClone($surveyId){

		try{
			DB::beginTransaction();
			$orgId = Auth::user()->organization_id;


			DB::select('CREATE TABLE cloning_survey as SELECT * FROM `'.$orgId.'_surveys` WHERE id = '.$surveyId);
			$newSurveyID = DB::select('SELECT MAX(id) as maxId FROM `'.$orgId.'_surveys`');
			$newSurveyID = $newSurveyID[0]->maxId + 1;
			DB::update('UPDATE cloning_survey SET id = '.$newSurveyID);
			DB::insert('INSERT into '.$orgId.'_surveys SELECT * FROM cloning_survey');
			DB::select('DROP TABLE cloning_survey');


			DB::select('CREATE TABLE cloning_group as SELECT * FROM `'.$orgId.'_survey_question_groups` WHERE survey_id = '.$surveyId);
			$newGroupID = DB::select('SELECT MAX(id) maxId FROM `'.$orgId.'_survey_question_groups`');
			$newGroupID = $newGroupID[0]->maxId + 1;
			DB::select('ALTER TABLE cloning_group ADD new_ids VARCHAR(255)');
			DB::select('SET @a = '.$newGroupID);
			DB::select('UPDATE cloning_group SET new_ids = @a:=@a+1');
			DB::select('UPDATE cloning_group SET survey_id = '.$newSurveyID);


			DB::select('CREATE TABLE cloning_question as SELECT * FROM `'.$orgId.'_survey_questions` WHERE survey_id = '.$surveyId);
			$maxQuestId = DB::select('SELECT MAX(id) maxId FROM `'.$orgId.'_survey_questions`');
			$maxQuestId = $maxQuestId[0]->maxId + 1;
			DB::select('SET @a = '.$maxQuestId);
			DB::select('UPDATE cloning_question SET id = @a:=@a+1');
			DB::update('UPDATE cloning_question SET survey_id = '.$newSurveyID);
			DB::select('UPDATE cloning_question cq JOIN cloning_group cg ON cg.id = cq.group_id SET cq.group_id = cg.new_ids');
			

			DB::select('ALTER TABLE cloning_group DROP COLUMN new_ids');
			DB::select('SET @a = '.$newGroupID);
			DB::select('UPDATE cloning_group SET id = @a:=@a+1');
			DB::select('INSERT into '.$orgId.'_survey_question_groups SELECT * FROM cloning_group');
			DB::select('DROP TABLE cloning_group');
			DB::select('INSERT into '.$orgId.'_survey_questions SELECT * FROM cloning_question');
			DB::select('DROP TABLE cloning_question');
			DB::commit();
			return ['status'=>'success','message'=>'Survey cloned successfully!'];
		}catch(\Exception $e){
			DB::rollback();
			throw $e;
		}
	}
	
}