<?php
namespace App\Helpers;
use App\SurveyQuestion as SQ;
use App\Surrvey;
use DB;



class MyFuncs{

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
}

?>