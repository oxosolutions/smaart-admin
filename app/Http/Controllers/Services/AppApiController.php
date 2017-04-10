<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Surrvey;
use Auth;
use App\organization as ORG;
use App\User;
use Session;
use App\SurveyQuestionGroup as SQG;
use App\SurveyQuestion as SQ;
use App\FileManager as FM;
use SurveyHelper;

class AppApiController extends Controller
{
    public function getAllsurveys(){
		try{
			$sdata = Surrvey::get();

			$survey['survey_id'] 				= 	$sdata->id;
			$survey['survey_name'] 				= 	$sdata->name;
	  		$survey['survey_author_id']			= 	$sdata->created_by;
	  		$survey['survey_author_name']		= 	$sdata->creat_by->name;
	  		$survey['survey_author_role_id']	= 	$sdata->creat_by->role_id;
	  		$survey['survey_author_role_name']	= 	$sdata->creat_by->roles->name;

	  		$survey['survey_description'] 		=	$sdata->description;
	  		$survey['survey_status'] 			= 	$sdata->status;
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
		Session::put('org_id',$model->id);
		$users = User::where('organization_id',$model->id)->get();
		$surveyData = Surrvey::where('status','1')->get();
		$groupsData = SQG::get();
		$surveyQuestions = SQ::get();
		$media ="";
		

		foreach ($surveyQuestions as $key => $value) {
			
			$questionData[$key]['question_id'] = $value->id;
			$ansData = json_decode($value->answer,true);
			

			$survey_media = SurveyHelper::get_survey_media($ansData['question_desc']);
			 if($survey_media['media']!=null)
			 {
			 	 if(is_array($survey_media['media']))
			 	 {
			 	 	foreach ($survey_media['media'] as $mkey => $mvalue) {
			 	 		$media[$mkey] = $mvalue;
			 	 	}
			 	 }
			 }

			// if(str_contains($ansData['question_desc'], ['[image_', '[audio_' ] ))
			// {	
			//  	$descData = 	explode(' ', $ansData['question_desc']);
			//  	foreach ($descData as $deskey => $desValue) {

			//  		 if(starts_with($desValue, '[image_'))
			//  		 {
			//  		 	 $newDec = str_replace('[', '', $desValue);
			//  		 	  $finalDes = str_replace(']', '', $newDec);
			//  			 $url = FM::select('url')->where('media',$finalDes);
			// 	 		 if($url->count()>0)
			// 	 		 {
			// 	 		  $urls =	$url->first()->url;
			// 	 		  $media[$finalDes] = $urls;
			// 	 		 }
			//  		 }
			//  		 elseif(starts_with($desValue, '[audio_'))
			//  		 {
			//  		 	//dump($desValue);
			//  		 	$newDec = str_replace('[', '', $desValue);
			//  		 	$finalDes = str_replace(']', '', $newDec);
			//  			 $url = FM::select('url')->where('media',$finalDes);
			// 	 		 if($url->count()>0)
			// 	 		 {
			// 	 		  $urls =	$url->first()->url;
			// 	 		 //  dump($urls);

			// 	 		  $media[$finalDes] = $urls;
			// 	 		 }
			//  		 }
			//  	}

			// }



			$questionData[$key]["next_question_key"] =  @$ansData["question_key"];
			$questionData[$key]["question_key"] =  @$ansData["question_id"];
    		$questionData[$key]["question_order"]	=  "";
    		$questionData[$key]['question_text'] = $value->question;
    		$questionData[$key]["question_desc"] = @$ansData['question_desc'];
    		$questionData[$key]["question_type"] = @$ansData['question_type'];
    		$questionData[$key]["question_message"]= "";
    		$questionData[$key]["required"]=  @$ansData['required'];
    		$questionData[$key]["pattern"]=  @$ansData['pattern'];

     		$option=null;
     		$ary = [];
		if(array_key_exists('extraOptions', $ansData))
		{
			$i=0;
			//dump($ansData['extraOptions']);
    		 foreach ($ansData['extraOptions'] as $optKey => $optValue) {
    		 		//dump($optKey);
    		 		//dump($optValue["options"]['label']);//['options']['label']); 
    			$option["option_type"] = $ansData['question_type'];
				$option["option_text"] = $optValue["options"]['label'];
       			$option["option_value"] =  $optValue["options"]['value'];
        		$option["option_next"] = 	$optValue["options"]['condition'];
        		$option["option_prompt"] = "";
        		array_push( $ary , $option);
    		}
    	}
			if($option==null)
			{
			   	$option["option_type"]= "";
				$option["option_text"] ="";
				$option["option_value"] =$ansData['extraOptions'];
				$option["option_next"] = "";
				$option["option_prompt"]= "";
			}
    	
    
    		$questionData[$key]["answers"][0]=  @$ary;
    // "question_type": "text",
    // "question_message": "",
    // "required": 0,
    // "answers": [
    //   {
    //     "option_type": "text",
    //     "option_text": "",
    //     "option_value": "",
    //     "option_next": "108",
    //     "option_prompt": ""
    //   }
    // ]
   

			// foreach ($ansData as $ansKey => $ansValue) {
			// 	$questionData[$key][$ansKey] = $ansValue;
			// }
			$questionData[$key]['group_id'] = $value->group_id;
			$questionData[$key]['survey_id'] = $value->survey_id;
			$questionData[$key]['created_at'] = $value->created_at;
			$questionData[$key]['updated_at'] = $value->updated_at;
			$questionData[$key]['deleted_at'] = $value->deleted_at;
		}
	 
		Session::forget('org_id');
		return ['status'=>'success', 'media'=>$media ,'questions'=>$questionData ,'users'=>$users,'surveys'=>$surveyData,'groups'=>$groupsData];
	}
protected function get_media($parm , $find)
{
	if(str_contains($parm,  $find ))
			{	
							 $descData = 	explode(' ', $parm);
							 	foreach ($descData as $deskey => $desValue) {

							 		 if(starts_with($desValue,  $find))
							 		 {
							 		 	 $newDec = str_replace('[', '', $desValue);
							 		 	  $finalDes = str_replace(']', '', $newDec);
							 		 	 // dump($finalDes);
							 			 $url = FM::select('url')->where('media',$finalDes);
								 		 if($url->count()>0)
								 		 {
								 		 	
								 		  $urls =	$url->first()->url;
								 		  //dump($urls);
								 		  $media[$finalDes] = $urls;
								 		 	
								 		 }

							 		 }
							 		 
							 	}

			}

}

}
