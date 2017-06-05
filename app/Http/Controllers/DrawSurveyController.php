<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
use Carbon\Carbon;


class DrawSurveyController extends Controller
{

    Public function survey_save_data($id)
    {
       $sdata = SurveyHelper::survey_save_data($id);
       return view('survey.survey_save_data',['sdata'=>$sdata['data'] , 'sid'=>$id]);
    }

    public function export_survey_data($id)
    {
         $data = SurveyHelper::survey_save_data($id);
         $model = $data['data']; 
         Excel::create($data['table'], function($excel) use ($model) {
              $excel->sheet('mySheet', function($sheet) use ($model)
                {
                    $sheet->fromArray($model);
                });
            })->download('csv');
    }
    
    public function survey_statistics($token)
    {
//         function multidimensional_search($parents, $searched) { 
//             if (empty($searched) || empty($parents)) { 
//                 return false; 
//             } 

//             foreach ($parents as $key => $value) { 
//                 $exists = true; 
//                 foreach ($searched as $skey => $svalue) { 
//                     $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue); 

//                 } 
//                 if($exists){ 
//                     $m[] =$key; 
//                 } 
//             } 

//             if($exists){ 
//                 dump($m);
//             }

//             return false; 
//         } 

//         $parents = array(); 
//         $parents[] = array('date'=>1320883200, 'uid'=>3,'new'=>'new'); 
//         $parents[] = array('date'=>1318204800, 'uid'=>5,'new'=>'new'); 
//         $parents[] = array('date'=>1318204800, 'uid'=>5,'new'=>'new'); 

//         echo $key = multidimensional_search($parents, array('date'=>1318204800, 'uid'=>5)); // 1 

// die;

        $data = SEMBED::where('embed_token',$token)->first();
        if($data == null){
            $errors[] = 'Survey id not valid!';
            return view('survey.draw_survey',['err_msg'=>$errors]);
        }
        $sid = $data->survey_id;
        Session::put('org_id', $data->org_id);
        $survey_data = Surrvey::find($sid);

        if(Schema::hasTable($survey_data->survey_table))
        {
            $survey_data['created_on'] = Carbon::parse($survey_data->created_at)->diffForHumans();
            $filled_data = DB::table($survey_data->survey_table);
            $survey_data['total_filled'] = $total_filled = $filled_data->count();     
            $survey_data['completed_survey'] =  $filled_data->where('survey_status',1)->count();     
            $survey_data['pending_survey'] =  $filled_data->where('survey_status',0)->count();     
           // $survey_data['pending_survey'] =  $filled_data->select()->groupBy('survey_submitted_by');

            $survey_data['user_filled_count'] = DB::table($survey_data->survey_table)->selectRaw('count(id) as total , survey_submitted_by')->groupBy('survey_submitted_by')->pluck('total','survey_submitted_by');
        }

        return view('survey.stats', ['survey_data'=>$survey_data]);
    }  

    public static function getSettings(Array $settingsArray, $keyValue){
        $keyArray = array_map(function($array) use ($keyValue){
            if($array['key'] == $keyValue){
                return $array;
            }
        }, $settingsArray);

        return $keyArray[array_search($keyValue, array_column($settingsArray, 'key'))]['value'];
    }
    protected function surveyViewType($surveyViewType , $token , $sid , $request=null )
    {
        if (!empty($request) && $request->isMethod('post'))
        {
            $viewType['token'] = $request['token']; 
            $viewType['type'] = $request['type'];
            $viewType['group_no'] = $request['group_no'];
            if($surveyViewType=="question")
            {
                $number = $request['number'];
                $group_id = $request['group_id'];
                $ques_count = SQ::where('group_id', $group_id)->count();
                $gcount =  GROUP::where('survey_id',$sid)->count();

                if(isset($request['next']))
                {
                    $number++;
                    $id = $this->survey_store($request);
                    $viewType['filled_id'] = $id;
                    $viewType['ques_filled_count'] = $number + @Session::get('que_count');

                }else if(isset($request['previous'])){
                    $number--;
                     Session::put('number',$number);
                    $viewType['filled_id'] = session::get('filled_id');
                }
                
                Session::put('number',$number);
                $viewType['number'] = $number;

               if($ques_count == $number)
                {
                    Session::put('que_count',$number);
                  $group_no = $viewType['group_no'];
                  $group_no++;
                  $viewType['group_no'] =$group_no;
                  $viewType['number'] = 0;
                  Session::put('number',0);
                }

                if($gcount== $viewType['group_no'])
                {
                    Session::forget(["filled_id", 'table','que_count']);
                    Session::flash('successfullSaveSurvey','Survey saved successfully!');
                }
            }

            if($surveyViewType=="group")
            {                   

                $ques_count = SQ::where('group_id', $request['group_id'])->count();
                $viewType['ques_filled_count'] = $ques_count + @Session::get('que_count');
                Session::put('que_count',$ques_count);
                $gcount =  GROUP::where('survey_id',$sid)->count();
                $group_no = $request['group_no'];
                if(isset($request['next']))
                {
                   $id =  $this->survey_store($request);
                    $viewType['filled_id'] = $id;
                    $group_no++;
                }else if(isset($request['previous'])){

                    $viewType['filled_id'] = session::get('filled_id');
                    $group_no--;
                } 
                if($gcount==$group_no)
                {
                    Session::forget(["filled_id", 'table','que_count']);
                    Session::flash('successfullSaveSurvey','Survey saved successfully!');
                }
                $viewType['group_no'] =$group_no;
            }

            if($surveyViewType=="survey")
            {
                $this->survey_store($request);
            }
         }else if($surveyViewType=="question")
        {   
            $viewType['group_no'] = 0;
            $viewType['token'] =   $token;
            $viewType['type'] = 'question';
            $group = $number =    $viewType['number'] = 0;
        }else if($surveyViewType=="group")
        {   
            $viewType['group_no'] = 0;
            $viewType['token'] =   $token;
            $viewType['type'] = 'group';
        }elseif($surveyViewType=="survey" || $surveyViewType ==null)
        {
            $viewType['type'] = 'survey';
            $viewType['token'] =   $token;
        }

        return $viewType;
    }

    public function reset_survey($token)
    {
        Session::forget(['table' ,'filled_id']);
        return redirect()->route('survey.nxt',['token'=>$token]);
    }

    protected function survey_filled_data()
    {

        if(!Schema::hasTable(Session::get('table'))){
            Session::forget(['table' ,'filled_id']);
        } else{
             $check =  DB::table(Session::get('table'))->where('id',Session::get('filled_id'));
            if($check->count()==0)
            {
                Session::forget(['table' ,'filled_id']);
            }
        }

        $filled_data = null;
        $c=0;
         if(!empty(Session::get('filled_id')) && !empty(Session::get('table')))
         {
            $c=0;
            $filled_data = DB::table(Session::get('table'))->where('id',Session::get('filled_id'))->first();
            foreach ($filled_data as $fkey => $fvalue) {
                if(!empty($fvalue))
                {
                   // dump($fvalue);
                    $c++;
                }
            }
        } 
         $real_count = $c - 10; 
         //dump('count'.$real_count); 
         return $filled_data;
    }
    public function draw_survey( Request $request , $token=null, $theme=null ,$skip_auth = null  )
    {
        Session::forget('successfullSaveSurvey');
        $group = null;
        if ($request->isMethod('post'))
        {
            $token = $request['token']; 
            $req = $request;
         }
        else{
                $req =null;
            }
        Surrvey::$group_take = null;
        Surrvey::$group_random = true;

        GROUP::$question_take = null;
        GROUP::$question_random = true;
        $ip = \Request::ip();
    	$data = SEMBED::where('embed_token',$token)->first();
        if($data == null){
            $errors[] = 'Survey id not valid!';
            return view('survey.draw_survey',['err_msg'=>$errors]);
        }
    	$sid = $data->survey_id;
    	Session::put('org_id', $data->org_id);
        $errors = [];
         
        $survey_settings = SSETTING::where(["survey_id" => $sid ])->get()->toArray();
        $surveyViewType = $this->getSettings($survey_settings,'surveyViewType');
        
        $viewType = null;
        $viewType = $this->surveyViewType($surveyViewType, $token, $sid, $req);
        $filled_data = $this->survey_filled_data();
        //dump($filled_data);
       // dump(Session::get('filled_id'));
      //dump(Session::get('table'));
       //dd($viewType);
    	$survey_data = Surrvey::with(['group'=>function($query)use($viewType) {
            if($viewType['type']=="survey")
            {
                $query->orderBy('group_order')->with(['question'=>function($que) {
                        $que->orderBy('quest_order');                            
                }]);
            }
            else{
                    $query->orderBy('group_order')->skip($viewType['group_no'])->take(1)->with(['question'=>function($que) use($viewType){
                            if(isset($viewType['number']))
                            {
                                $que->orderBy('quest_order')->skip($viewType['number'])->take(1);
                            }else{
                                $que->orderBy('quest_order');
                            }
                        }]);
                }
                    }])->find($sid);

       // dd($survey_data);

        if($survey_data == null){
            $errors[] = 'Survey id not valid!';
            return view('survey.draw_survey',['err_msg'=>$errors]);
        }
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
                            if(is_array($roles) && !empty($roles)){
                                if(!in_array(Auth::user()->role_id, $roles)){
                                    $errors[] = $messages_list['survey_unauth_role'];
                                }
                            }else{
                                $errors[] = $messages_list['survey_unauth_role'];
                            }
                            
                        }else{
                            $users = [];
                            $users = json_decode($this->getSettings($survey_settings,'authorized_users'),true);
                            if(is_array($users) && !empty($users)){
                                if(!in_array(Auth::user()->id, $users)){
                                    $errors[] = $messages_list['survey_unauth_user'];
                                }
                            }else{
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
             $today_date = date('Y-m-d h:i:s');
            //dump($today_date);
        $survey_start_date = $this->getSettings($survey_settings,'survey_start_date');
        $survey_expiry_date = $this->getSettings($survey_settings,'survey_expiry_date');
           // dump($survey_start_date);
            //dump($survey_expiry_date);

           if($today_date < $survey_start_date){
                $errors[] = $messages_list['survey_not_started'];
            }elseif($today_date > $survey_expiry_date){
                $errors[] = $messages_list['survey_expired'];
            }
            
        }
//progress Bar setting 
                $progress_bar_question =null;
                $progressbar_status = $this->getSettings($survey_settings,'showProgressbar');
                if(($progressbar_status == 1 || $progressbar_status == '1') && $progressbar_status != null){
                    $progress_bar_question = $this->progress_bar($sid);
                   
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
            return view('survey.draw_survey',[ 'custom_code'=>$custom_code, 'timer'=>$timer ,'theme'=>$theme , 'skip_auth'=>$skip_auth , 'sdata'=>$survey_data, 'token'=>$token, 'design_settings'=>$survey_settings , 'progress_bar_question'=>$progress_bar_question,'viewType'=>$viewType, 'filled_data'=>$filled_data,'sid'=>$sid]);
        }

    	
    }

    protected function progress_bar($sid)
    {
        return SQ::where('survey_id',$sid)->count();
    }

    public function survey_store($request)
    {
       // dd($request->all());
       // try{
       $type = $request['type'];
      
        unset($request['token'], $request['type'], $request['group_id'], $request['group_no'], $request['number'], $request['next']);
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

            if($type=='survey')
            {
                 unset($insert['filled_id'],$insert['code']);
                DB::table($table)->insertGetId($insert);
                  Session::flash('successfullSaveSurvey','Survey saved successfully!');
             return redirect()->route('survey.draw',['id'=>$request->code]);
            }
            else if(isset($request['filled_id']))
            {   $id = $request['filled_id'];
                unset($insert['filled_id'],$insert['code']);
                DB::table($table)->where('id',$id)->update($insert);
                Session::put("filled_id",$id);
                return $id;
            }else{
                    unset($insert['code']);
                    $id  = DB::table($table)->insertGetId($insert);
                    Session::put(["filled_id"=>$id, 'table'=>$table]);
                    return $id;
                }

            // dump('inser_id' . $id);
            // die;
            
        //     Session::flash('successfullSaveSurvey','Survey saved successfully!');
        //     return redirect()->route('survey.draw',['id'=>$request->code]);
        // }catch(\Exception $e)
        // {
        //     return ['status'=>'error', 'message'=>"Something goes wrong try again"];
        // }
    }

    public function view_filled_survey($sid , $uid)
    {	

    	$table = "_survey_data_".$sid;
    	$data = DB::table($table)->where('created_by',$uid)->first();
    	$Ques = SQ::where('survey_id',$sid)->get();
    	foreach ($Ques as $key => $value) {
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
            return redirect()->route('survey.nxt',['id'=>Session::get('token')]);
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
