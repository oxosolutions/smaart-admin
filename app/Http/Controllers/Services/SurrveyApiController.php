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
use MyFuncs;

class SurrveyApiController extends Controller
{

// 	public function view_survey_result( )
// 	{
// 		$org_id = Auth::user()->organization_id;
// 		$table = $org_id."_survey_data_54";
//     	$data = DB::table($table)->get();
// //     	$Ques = SQ::where('survey_id',$sid)->get();
// // //dump($data->SID13_GID1_QID1);
// //     	foreach ($Ques as $key => $value) {
// //     		# code...
// //     		echo "<br>Question :".$value->question.'<br>';
// //     		$ans = json_decode($value->answer);
// //     		  $qid = $ans->question_id;
// //     		  echo "Answer ".$data->$qid.'<br><br>';
// //     	}	
// return ['status'=>"success" , "data"=>$data];	
// 	}

	public function save_survey_filled_data(Request $request)
	{
		dump($request->all());
		$org_id = org::select('id')->where('activation_code' ,$request->activation_code)->first()->id;
		Session::put('org_id', $org_id);
		$data = json_decode($request->export,true);
		$surveyid = $data[0]["surveyid"];
		$table = $org_id.'_survey_data_'.$surveyid;
		if(!Schema::hasTable($table))
    	{
    		//MyFuncs::create_survey_table($surveyid, $org_id);
    		MyFuncs::create_survey_table($surveyid , $org_id);


   //  		$ques_data = SQ::select(['answer'])->where('survey_id',$surveyid)->get();
   //  		foreach ($ques_data as $key => $value) {
   //  		 $ans = json_decode($value->answer);
   //  		 $colums[] =   "`$ans->question_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL";
   //  		}
			// $colums[] =    "`ip_address` varchar(255) NULL DEFAULT  NULL";
			// $colums[] =    "`survey_start_on` timestamp NULL DEFAULT  NULL";
			// $colums[] =    "`survey_completed_on` timestamp NULL DEFAULT  NULL";
			// $colums[] =    "`survey_status` int(1) NULL DEFAULT  NULL";
			// $colums[] =    "`survey_submited_by` varchar(255) NULL DEFAULT  NULL";
			// $colums[] =    "`survey_submited_from` varchar(255) NULL DEFAULT  NULL";
			// $colums[] =    "`mac_address` varchar(255) NULL DEFAULT  NULL";
			// $colums[] =    "`imei` varchar(255) NULL DEFAULT  NULL";
			// $colums[] =    "`unique_id` varchar(255) NULL DEFAULT  NULL";
			// $colums[] =    "`created_by` int(11)  NULL";
			// $colums[] =    "`created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP";


			// DB::select("CREATE TABLE `{$table}` ( " . implode(', ', $colums) . " ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
   //      	DB::select("ALTER TABLE `{$table}` ADD `id` INT(100) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Row ID' FIRST");
   //      	Surrvey::where('id',$surveyid)->update(['surrvey_table'=>$table]);
		}
		foreach ($data as $key => $value) {
				
			
			//dd($value['starton']);
			//8888 $insert['survey_start_on'] =date("Y-m-d", strtotime($value['starton']));
			// $insert['survey_completed_on'] =$value['endon'];
			// if($value['status'] =="completed")
			// {
			// 	$status =1;
			// }else{
			// 	$status = 0;
			// }
			// $insert['survey_status'] = $status;
			// $insert['survey_submited_by'] =$value[];
			// $insert['survey_submited_from'] =$value[];
			// $insert['mac_address'] =$value[];
			// $insert['imei'] =$value[];
			// $insert['unique_id'] =$value[];

			foreach ($value['answers'] as $key => $value) {
				
				if(is_array($value["answer"]))
				{
					$ansdata="";
						foreach ($value["answer"] as $ansKey => $ansValue) {
							$ansdata[] = $ansKey;
						}
					$ans = json_encode($ansdata);
				}else{
					$ans = $value["answer"];
				}
				
				$insert[$value['questkey']] = $ans;
				$insert["ip_address"] = $request->ip();
				
			}
			
			DB::table($table)->insert($insert);
		
		}

		
	}
//VIEW SURVEY SAVED DATA 
	public function view_survey_saved_data($sid)
	{
		try{
		Surrvey::findORfail($sid);
		$table = Surrvey::select('surrvey_table')->where('id',$sid)->first()->surrvey_table;
		$data = DB::table($table)->get();
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


	//SAVE & EDIT SURVEY GROUP QUESTION
	public function save_survey_data(Request $request)
	{
		
		$survey_id = $request['survey_id'];
		$grp = GROUP::where('survey_id',$survey_id);
		$sq = SQ::where('survey_id', $survey_id);		
		$data = json_decode($request['survey_data'],true);
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
// DRAW SURVEY 

	// FOR VIEW GROUP & QUESTION 
	public function view_survey_data($id)
	{
	try{
			Surrvey::findORfail($id);
			$sdata = Surrvey::find($id);		
			$survey['id'] 			= 	$sdata->id;
			$survey['survey_name'] 	= 	$sdata->name;
	  		$survey['created_by']	= 	$sdata->created_by;
	  		$survey['user_name']	= 	$sdata->creat_by->name;
	  		$survey['description'] 	=	$sdata->description;
	  		$survey['status'] 		= 	$sdata->status;
	  		if(count($sdata->group)>0){
				foreach ($sdata->group as $key => $grp) {
					$survey['group'][$key]['group_id'] 			= $grp->id;
					$survey['group'][$key]['survey_id'] 		= $grp->survey_id;
					$survey['group'][$key]['group_name'] 		= $grp->title;
		    		$survey['group'][$key]['group_description'] =$grp->description;			
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
			//dd($sdata->creat_by->roles->name);	
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

		try{
			$user_id =	Auth::user()->id;
			$org_id = Auth::user()->organization_id;
            $surrvey = new Surrvey();
            $surrvey->name = $request->name;
            $surrvey->description = $request->description;
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
            $survey['description'] =	$model->description;
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
		          		if($key == "survey_custom_error_messages_list" && $ssdata['survey_custom_error_message_status'] != null)
          		{
          			$value = json_encode($value);
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>$value];
          			$this->save_survey_setting($settingdata);
          		}else if($ssdata['survey_custom_error_message_status'] == null && $key =="survey_custom_error_messages_list")
          		{

          		}elseif($key =="authorized_roles" && $value !=null)
          		{
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>json_encode($value)];
          			$this->save_survey_setting($settingdata);

          		}else if($key =="authorized_users" && $value !=null)
          		{
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>json_encode($value)];
          			$this->save_survey_setting($settingdata);
          		}
				else{
          			$settingdata = ['survey_id'=>$sid,'key'=>$key, 'value'=>$value];
          			$this->save_survey_setting($settingdata);
	          		}
          		}
    	}
//  UPDATE SURVEY & SETTING
	public function survey_update(Request $request )
	    {

	        try{
	        	DB::beginTransaction();
	            Surrvey::findORfail($request->id);
	            	$status = "0";
	            if($request->enableDisable=="true")
	            {
	            	$status = "1";
	            }
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
}
