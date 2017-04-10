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
use SurveyHelper;
use Excel;


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
        Surrvey::$group_take = null;
        Surrvey::$group_random = null;

        GROUP::$question_take = null;
        GROUP::$question_random = null;
        $ip = \Request::ip();
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
        
        $error_message_status = $this->getSettings($survey_settings,'survey_custom_error_message_status');
        if($error_message_status =='0')
        {

            $messages_list = [
                              "responce_limit_exceeded" => "Responce limit exceeded..",
                              "survey_expired" => "Survey is expired.",
                              "survey_not_started" => "Survey not started yet.",
                              "empty_survey" => "Empty survey.",
                              "invalid_survey_id" => "Invalid survey ID.",
                              "survey_unauth_user" => "You do not have permissions to access the survey.",
                              "survey_unauth_role" => "Your user role do not have permissions access the survey.",
                              "survey_auth_required" => "You have to login to access the survey.",
                              "survey_status" => "Survey is disabled."
                            ];
                            
        }else{
            $messages_list = json_decode($this->getSettings($survey_settings,'survey_custom_error_messages_list'),true);
            
        }
        if($survey_data->status == 1){
//RESPONSE PER IP CHECK
            $response_status = $this->getSettings($survey_settings,'survey_respone_limit_status');

                            if($response_status =='1' || $response_status ==1 || $response_status !='0' )
                            {
                                $response_type = $this->getSettings($survey_settings,'survey_response_limit_type');
                                if($response_type=='per_ip_address')
                                {
                                    $sid = $data->survey_id;
                                    $table = $data->org_id."_survey_data_".$sid;
                                     $filled_count = DB::table($table)->where('ip_address',$ip)->count();
                                   
                                     $response_value = $this->getSettings($survey_settings,'survey_response_limit_value');
                                     if($response_value <= $filled_count)
                                     {
                                         $errors[] = $messages_list['responce_limit_exceeded'];
                                     }
                                }
                            }

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
//Response limit Per user                           
                            $response_status = $this->getSettings($survey_settings,'survey_respone_limit_status');
                            if($response_status =='1' || $response_status ==1 || $response_status !='0' )
                            {
                                $response_type = $this->getSettings($survey_settings,'survey_response_limit_type');
                                if($response_type=='per_user')
                                {
                                    $sid = $data->survey_id;
                                    $table = $data->org_id."_survey_data_".$sid;
                                    $filled_count = DB::table($table)->where('survey_submitted_by',Auth::user()->id)->count();
                                   
                                    $response_value = $this->getSettings($survey_settings,'survey_response_limit_value');
                                    if($response_value <= $filled_count)
                                    {
                                        $errors[] = $messages_list['responce_limit_exceeded'];
                                    }
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
            $errors[] = @$messages_list['survey_status'];
        }
        
        $scheduling_status = $this->getSettings($survey_settings,'survey_scheduling_status');
        if(($scheduling_status == 1 || $scheduling_status == '1') && $scheduling_status != null){
             $today_date = date('Y-m-d H:i:s');
            
        $survey_start_date = $this->getSettings($survey_settings,'survey_start_date');
        $survey_expiry_date = $this->getSettings($survey_settings,'survey_expiry_date');
           // dump('start'.$survey_start_date);
                        //dump('end'.$survey_expiry_date);


            if($today_date < $survey_start_date){
                $errors[] = $messages_list['survey_not_started'];
            }elseif($today_date > $survey_expiry_date){
                $errors[] = $messages_list['survey_expired'];
            }
            
        }
            @$timer['survey_timer_status'] = $this->getSettings($survey_settings,'survey_timer_status');
            @$timer['survey_timer_type']   = $this->getSettings($survey_settings,'survey_timer_type');
            @$timer['survey_duration']     = $this->getSettings($survey_settings,'survey_duration');
            @$timer['survey_expiry_date']  = $survey_expiry_date;
           
            @$custom_code['custom_js'] = $this->getSettings($survey_settings,'customJs');
            @$custom_code['custom_css'] = $this->getSettings($survey_settings,'customCss');

                    
    	
        if(!empty($errors)){
            return view('survey.draw_survey',['err_msg'=>$errors,'theme'=>$theme, 'skip_auth'=>$skip_auth ,'token'=>$token]);
        }else{
            return view('survey.draw_survey',['custom_code'=>$custom_code, 'timer'=>$timer ,'theme'=>$theme , 'skip_auth'=>$skip_auth , 'sdata'=>$survey_data, 'token'=>$token]);
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
            $insert["unique_id"] = $data->survey_id.''.date('YmdHis').''.substr((string)microtime(), 2, 6).''.rand(1000,9999); 
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
