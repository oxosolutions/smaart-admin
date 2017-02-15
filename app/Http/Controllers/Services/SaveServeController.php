<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Schema;

class SaveServeController extends Controller
{
    
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
}
?>