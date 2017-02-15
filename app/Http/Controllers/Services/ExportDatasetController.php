<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DatasetsList as DL;
use Excel;
use DB;
use Illuminate\Support\Facades\Schema;


class ExportDatasetController extends Controller
{
    public function export($dataset_id, $type){
     
        
        try{
            $model = DL::find($dataset_id);
            $table_name = $model->dataset_table; 
            if(Schema::hasTable($table_name))
            {
                $name  =  str_replace(" ","-", $model->dataset_name); 
                $datas =   DB::table($table_name)->get()->toArray();
                $model =   json_decode(json_encode($datas),true);
           
                $headers = $model[0];
                foreach ($model as $key =>  $value) {
                      $model[$key] = array_combine($headers, $value);
                      unset($model[$key][1]);
                      unset($model[0]);
                  
                 }

                Excel::create($name, function($excel) use($model) {
                    $excel->sheet('Sheetname', function($sheet) use($model) {
                    $sheet->fromArray($model);
                    });
                })->store($type);

               return $this->downloadFile($name ,$type);
            }else{
                return ['status'=>'error','message'=>'Something happen wrong Try Again..'];
            }
         }catch(\Exception $e)
        {
           return ['status'=>'error','message'=>'Something happen wrong Try Again..'];
        }


    }

    public function downloadFile($fileName ,$type){

    	$path = storage_path('exports/'.$fileName.'.'.$type);
        return response()->download($path,$fileName.'.'.$type,['Content-Type: text/cvs']);

    }

    private function objectToArray($objectArray){
    	$arrays = [];
    	foreach($objectArray as $object){
		    $arrays[] =  (array) $object;
		}

		return $arrays;
    }
}
