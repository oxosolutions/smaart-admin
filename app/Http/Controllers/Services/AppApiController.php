<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Surrvey;
use Auth;
use App\organization as ORG;
use App\User;
class AppApiController extends Controller
{
    public function getAllsurveys(){
		try{

			$sdata = Surrvey::get();
			;	
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

	public function activateApp(Request $request){

		$activationCode = $request->activation_key;
		$model = ORG::where('activation_code',$activationCode)->first();
		if($model == null){
			return ['status'=>'error','message'=>'Wrong activation key!'];
		}

		$users = User::where('organization_id',$model->id)->get();

		return ['status'=>'success','users'=>$users];
	}
}
