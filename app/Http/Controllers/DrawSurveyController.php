<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\Surrvey;
use App\SurveyQuestion as SQ;
use Auth;
use App\UserMeta as um;
use DB;
use App\SurveyQuestionGroup as GROUP;
use App\SurveySetting as SSETTING;
use Illuminate\Support\Facades\Schema;
use App\SurveyEmbed as SEMBED;
use Session;
use MyFuncs;
use SurveyHelper;


class DrawSurveyController extends Controller
{
    
    protected function getSettings(Array $settingsArray, $keyValue){
        $keyArray = array_map(function($array) use ($keyValue){
            if($array['key'] == $keyValue){
                return $array;
            }
        }, $settingsArray);

        return $keyArray[array_search($keyValue, array_column($settingsArray, 'key'))]['value'];
    }

    public function draw_survey($token, $theme=null ,$skip_auth = null )
    {//2017-03-24T17:00:00.000Z
    	$data = SEMBED::where('embed_token',$token)->first();
        if($data == null){
            $errors[] = 'Survey id not valid!';
            return view('survey.draw_survey',['err_msg'=>$errors]);
        }
    	$sid = $data->survey_id;
    	Session::put('org_id', $data->org_id);
        $errors = [];
    	$survey_data = Surrvey::find($sid);
        if($survey_data == null){
            $errors[] = 'Survey id not valid!';
            return view('survey.draw_survey',['err_msg'=>$errors]);
        }
    	$survey_settings = SSETTING::where(["survey_id" => $sid ])->get()->toArray();
        $messages_list = json_decode($this->getSettings($survey_settings,'survey_custom_error_messages_list'),true);
        if($survey_data->status == 1){
            $authentication_required = $this->getSettings($survey_settings,'authentication_required');
            if($skip_auth == null){
                if(($authentication_required == '1' || $authentication_required == 1) && $authentication_required != null){
                    if(Auth::check()!=false){
                        $authentication_type = $this->getSettings($survey_settings,'authentication_type');
                        if($authentication_type == 'role'){
                            $roles = json_decode($this->getSettings($survey_settings,'authorized_roles'),true);
                            if(!in_array(Auth::user()->role_id, $roles)){
                                $errors[] = $messages_list['survey_unauth_role'];
                            }
                        }else{
                            $users = json_decode($this->getSettings($survey_settings,'authorized_users'),true);
                            if(!in_array(Auth::user()->id, $users)){
                                $errors[] = $messages_list['survey_unauth_user'];
                            }
                        }
                    }else{
                        Session::flash('error',$messages_list['survey_auth_required']);
                        Session::put('token',$token);
                        return redirect()->route('survey.auth');
                    }
                }
            }
        }else{
            $errors[] = ucfirst($messages_list['survey_status']);
        }
        
        $scheduling_status = $this->getSettings($survey_settings,'survey_scheduling_status');
        if(($scheduling_status == 1 || $scheduling_status == '1') && $scheduling_status != null){
            $today_date = date('Y-m-d H:i');
        $survey_start_date = $this->getSettings($survey_settings,'survey_start_date');
        $survey_expiry_date = $this->getSettings($survey_settings,'survey_expiry_date');

            if($today_date < $survey_start_date){
                $errors[] = $messages_list['survey_not_started'];
            }elseif($today_date > $survey_expiry_date){
                $errors[] = $messages_list['survey_expired'];
            }
            
        }
    	/*if($this->get_setting($sid,'survey_custom_error_message_status')!=null)
    	{
    		$msgList = json_decode($this->get_setting($sid,'survey_custom_error_messages_list'),true);
    		dump($msgList);
    	}
    	if($sdata->status ==0)
    	{
    		$msgList['survey_status'];
    		return view('survey.draw_survey',['err_msg'=>$msgList['survey_status']]);

    	}

    	if($this->get_setting($sid,'survey_scheduling_status')==1){
				$sdate =  $this->get_setting($sid,'survey_start_date');
				$exdate =  $this->get_setting($sid,'survey_expiry_date');

				
		if($this->get_setting($sid,'authentication_required')==1){
    		if(Auth::check()==false){
    			   return view('survey.draw_survey',['err_msg'=>@$msgList['survey_auth_required']]);
    			}
        //role chk
			if($this->get_setting($sid,'authentication_type')=='role'){
				$roles = json_decode($this->get_setting($sid,'authorized_roles'),true);
					 if(in_array(Auth::user()->role_id, $roles)==false)
					 {
					return view('survey.draw_survey',['err_msg'=>$msgList['survey_unauth_role']]);
 	
					 }
    		
    			}
    		if($this->get_setting($sid,'authentication_type')=='ind'){
				$users = json_decode($this->get_setting($sid,'authorized_users'),true);
					 if(in_array(Auth::user()->id, $users)==false)
					 {
					 	
					return view('survey.draw_survey',['err_msg'=>$msgList['survey_unauth_user']]);
 	
					 }
    			}
		}
        
		if(date('Y-m-d') >= $sdate || date('Y-m-d') <= $exdate )
						{
								//dd($sdate);
						}
						else{
							if(date('Y-m-d') >= $exdate)
							{
	       return view('survey.draw_survey',['err_msg'=>$msgList['survey_expired']]);
							
							}
					return view('survey.draw_survey',['err_msg'=>$msgList['survey_not_started']]);

						}
			}*/
        if(!empty($errors)){
            return view('survey.draw_survey',['err_msg'=>$errors,'token'=>$token]);
        }else{
            return view('survey.draw_survey',['theme'=>$theme, 'skip_auth'=>$skip_auth , 'sdata'=>$survey_data, 'token'=>$token]);
        }

    	
    }

    public function survey_store(Request $request)
    {
        try{
            $data = SEMBED::where('embed_token',$request->code)->first();

            if($data == null){
                $errors[] = 'Survey id not valid!';
                return view('survey.draw_survey',['err_msg'=>$errors]);
            }
            $table = $data->org_id.'_survey_data_'.$data->survey_id;
            $uid = $data->user_id;
            Session::put('org_id',$data->org_id);
    		if(!Schema::hasTable($table))
        	{
                SurveyHelper::create_survey_table($data->survey_id , $data->org_id);
    		}else{
                
               SurveyHelper::alter_survey_table($data->survey_id , $data->org_id);
           }

        	foreach ($request->all() as $key => $value) {
        		if($key=="_token" || $key=="survey_id" )
        		{ }
        		elseif(is_array($request->$key))
        		{
        			$insert[$key] = json_encode($value);
        		}else{
        			$insert[$key] = $request->$key;
        		}
        	}
            
            if(Auth::check())
            {
                $survey_submitted_by = Auth::user()->id;
            }
            else{ $survey_submitted_by = null; }
            $insert["survey_completed_on"] = date('YmdHis').substr((string)microtime(), 2, 6);
            $insert["survey_submitted_by"] = $survey_submitted_by;
            $insert["survey_submitted_from"] = "WEB";
            $insert["survey_status"] = 1;
            $insert["unique_id"] = date('YmdHis').''.substr((string)microtime(), 2, 6).''.rand(1000,9999); 
    		$insert["created_by"] = $uid;
        	$insert["ip_address"] = $request->ip();
            unset($insert['code']);
        	DB::table($table)->insert($insert);
            Session::flash('successfullSaveSurvey','Survey saved successfully!');
            return redirect()->route('survey.draw',['id'=>$request->code]);
        }catch(\Exception $e)
        {
            return ['status'=>'error', 'message'=>"Something goes wrong try again"];
        }
    }

    public function view_filled_survey($sid , $uid)
    {	

    	$table = "_survey_data_".$sid;
    	$data = DB::table($table)->where('created_by',$uid)->first();
    	$Ques = SQ::where('survey_id',$sid)->get();
//dump($data->SID13_GID1_QID1);
    	foreach ($Ques as $key => $value) {
    		# code...
    		echo "<br>Question :".$value->question.'<br>';
    		$ans = json_decode($value->answer);
    		  $qid = $ans->question_id;
    		  echo "Answer ".$data->$qid.'<br><br>';
    	}
    	
    }

    public function saveDataset(Request $request)
    {	
    	$data = json_decode($request->data, true);
    	foreach ($data as $key => $value) {
    			$new="";
    		 	$surrveyTable = 'surrvey_data_'.$value['surveyid'];
       		foreach ($value['answers'] as $key => $ansVal) {
   			
                if($ansVal['type']=="checkbox")
                {
            		foreach ($ansVal['answer'] as $key => $value) {
            			$c =$ansVal['questkey'].'_'.$key;
            			$assoc[] = $c;
		                $columns[] = "`{$c}` TEXT NULL";
		                $new[$c] = $value;
            		}                	
                }else{
            			$c = $ansVal['questkey'];
		                $assoc[] = $c;
		                $columns[] = "`{$c}` TEXT NULL";
	                	if(array_key_exists('answer', $ansVal))
	                	{
	                	  $new[$ansVal['questkey']] = $ansVal['answer'];
	                	}                	
                }
    		}
    		$newdata[] = $new;    		
    	}
    	$unique_column = array_unique($columns); 
    	if(!Schema::hasTable($surrveyTable))
   			{
    		    DB::select("CREATE TABLE `{$surrveyTable}` ( " . implode(', ', $unique_column) . " ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		        DB::select("ALTER TABLE `{$surrveyTable}` ADD `id` INT(100) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Row ID' FIRST");
		    }
        for($i=0; $i<count($newdata); $i++)
        {
         DB::table($surrveyTable)->insert($newdata[$i]);
        }
        return ['status'=>'success' , 'message'=>'Succefully save surrvey'];
	}

    public function login(){
        return view('survey.login');
    }

    public function do_auth(Request $request){

        $this->validateAuth($request);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            Session::put('survey_logined',$request->email);
            return redirect()->route('survey.draw',['id'=>Session::get('token')]);
        }
    }

    protected function validateAuth($request){

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $this->validate($request,$rules);
    }

    public function out($token)
    {
        Auth::logout();
        return redirect()->route('survey.draw',['id'=>$token]);

    }
}
