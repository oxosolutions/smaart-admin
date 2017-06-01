<?php
namespace App\Helpers;
use App\SurveyQuestion as SQ;
use App\Surrvey;
use DB;
use App\FileManager as FM;
use App\User;
use App\organization;
use App\Role;



class MyFuncs{

	Public static function user_detail_by_id($id)
	{
		$userDetail = User::find($id);
		return $userDetail;
	}
// Bind with Service/SurveyApiController -(view_survey_saved_data)
// Bind with DrawSurveyController -(survey_save_data , export_survey_data)
 	Public static function role_name_by_id($id)
 	{
 		return Role::where('id',$id)->select('name')->first()->name;		
 	}

 	Public static function user_name_by_id($id)
 	{
 		return User::where('id',$id)->select('name')->first()->name;		
 	}

	Public static function survey_save_data($sid)
	{
			$survey_data = Surrvey::with(['group'=>function($query) {
				$query->orderBy('group_order')->with(['question'=>function($que) {
					$que->orderBy('quest_order');                            
				}]);
			}])->find($sid);
			foreach ($survey_data->group as $gkey => $gvalue) {
				foreach ($gvalue->question as $qkey => $qvalue) {
					$decode = json_decode($qvalue->answer);
					$q_id =	$decode->question_id;
					$quesId[$q_id] = $q_id;
					$quesText[$q_id] = $qvalue->question;
					$quesType[$q_id] = 	$decode->question_type;

				}
			}
			$quesId['device_detail'] = 'device_detail';
			$quesType['device_detail'] = 'device_detail';
			$quesText['device_detail'] = 'Device Information';
			//dump($quesId);
			$newData[0] = $quesText;
			Surrvey::findORfail($sid);
			$table = Surrvey::select('survey_table')->where('id',$sid)->first()->survey_table;
			$data = DB::table($table)->get();
			foreach ($data as $key => $value) {
				$key++;
				//dump($value->device_detail);
				//unset($value->device_detail);
				foreach ($quesId as $qqkey => $qqvalue) {
					if(@$value->$qqvalue)
					{
						$newData[$key][$qqvalue] = $value->$qqvalue;
						$jsonData =	 json_decode($value->$qqvalue,true);

					}

						
				if(json_last_error() === JSON_ERROR_NONE)
					{	unset($optVal);
						 $qid = array_search('checkbox', $quesType);
						 if(!empty($value->device_detail))
						 {
						 		$devqid = array_search('device_detail', $quesType);
						}
						 if($qid==$qqvalue)
						 {
							if(!empty($jsonData)){
								foreach ($jsonData as $aKey => $ansValue) {
									if($ansValue==true)
									{
										$optVal[] = $aKey;
									}
								}
							}
						}elseif(@$devqid==$qqvalue){

							//dump($jsonData);
							foreach ($jsonData as $devkey => $devValue) {
								if(is_array($devValue))
								{
									foreach ($devValue as $infokey => $infoValue) {
										$optVal[] = "$infokey :  $infoValue";
									}
								}else{

									$optVal[] = "$devkey :  $devValue";
								}

								# code...
							}

							$optVal[] = 'NULL';
						}else{
							$optVal[] = 'NULL';
						}
					
						$newData[$key][$qqvalue] = implode(', ', $optVal);
					}		
				}
			}
			//dump($newData);
			//die;
			return ['data'=>$newData, 'table'=>$table];
		
	}

	Public function isJson($string) {
		 json_decode($string);
		 return (json_last_error() == JSON_ERROR_NONE);
	}

	

public static function create_survey_table($survey_id , $org_id)
	{
		$table = $org_id.'_survey_data_'.$survey_id;
		$ques_data = SQ::select(['answer'])->where('survey_id',$survey_id)->get();
			foreach ($ques_data as $key => $value) {
    		 $ans = json_decode($value->answer);
    		 $colums[] =   "`$ans->question_id` text COLLATE utf8_unicode_ci DEFAULT NULL";
    		}
			$colums[] =    "`ip_address` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`survey_started_on` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`survey_completed_on` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`survey_status` int(1) NULL DEFAULT  NULL";
			$colums[] =    "`survey_submitted_by` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`survey_submitted_from` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`mac_address` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`imei` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`unique_id` varchar(255) NULL DEFAULT  NULL";
			$colums[] =    "`device_detail` text NULL DEFAULT  NULL";
			$colums[] =    "`created_by` int(11)  NULL";
			$colums[] =    "`created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP";
			$colums[] =    "`deleted_at` timestamp NULL DEFAULT NULL";


			DB::select("CREATE TABLE `{$table}` ( " . implode(', ', $colums) . " ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        	DB::select("ALTER TABLE `{$table}` ADD `id` INT(100) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Row ID' FIRST");
        	Surrvey::where('id',$survey_id)->update(['survey_table'=>$table]);

	}

	public static function alter_survey_table($survey_id , $org_id)
	{
		$table = $org_id.'_survey_data_'.$survey_id;
		$existedColumn = DB::getSchemaBuilder()->getColumnListing($table);
		$ques_data = SQ::select(['answer'])->where('survey_id',$survey_id)->get();
		foreach ($ques_data as $key => $value) {
    		 $ans = json_decode($value->answer);
    		 $newColums[] =  $ans->question_id; 
    		}
		foreach ($newColums as $newKey => $newValue) {
    			if(!in_array($newValue,$existedColumn))
    			{

    			DB::select("ALTER TABLE `{$table}` ADD `$newValue` text COLLATE utf8_unicode_ci DEFAULT NULL AFTER id");

    			}
    			
    		}
	}

	public function get_media_value($strValue)
	{
		$newDec = str_replace('[', '', $strValue);
		$finalDes = str_replace(']', '', $newDec);

		$url = FM::select('url')->where('media',$finalDes);
		if($url->count()>0)
		{
		return   ['key'=>$finalDes , 'media'=>$url->first()->url]; 	
		}
	}

	public static function get_survey_media($string)
	{	
		$string_data = null;
		$media = null;
		$obj = new MyFuncs();	
		if(str_contains($string,  [ '[image_' , '[audio_'] ))
		{	
		 $string_data = 	explode(' ', $string);
		foreach ($string_data as $strkey => $strValue) {

			if(starts_with($strValue,"[image_"))	
			{
				$img = $obj->get_media_value($strValue);
				$string_data[$strkey] = "<img class='survey-media media-type-image' src='".$img['media']."'>";
				$media[$img['key']] = $img['media'];
				//unset($string_data[$strkey]);
			}
			elseif(starts_with($strValue,"[audio_"))
			{
				$audios = $obj->get_media_value($strValue);
				$string_data[$strkey] = "<audio class='survey-media media-type-audio'   controls>											<source src='".$audios['media']."' type='audio/ogg'>													       <source src='".$audios['media']."' type='audio/mpeg'>our browser does not support the audio element.	
										</audio>";
				$media[$audios['key']] = $audios['media'];

	 		}	
		}
	}
		if($string_data==null)
		{
			$text = $string;
		}else{
			$text = implode(" ", $string_data);
		}

	return ['text'=> $text , 'media'=>$media ];
}
}

?>