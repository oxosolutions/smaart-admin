<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class DatasetsList extends Model
{
    protected $table;
   public function __construct(){
      parent::__construct();

      if(Session::get('org_id') == null){
        foreach(Auth::user()->meta as $key => $value){
            if($value->key == 'organization'){
                $this->table = $value->value.'_datasets';
                break;
            }
        }
      }else{
        $this->table = Session::get('org_id').'_datasets';
      }
     
   }
    protected $fillable = ['dataset_name','dataset_records','uploaded_by'];

    public static function datasetList(){
    	return self::orderBy('id')->pluck('dataset_name','id');
    }

    public function userId(){
    	return $this->belongsTo('App\User','user_id','id');
    }

public function createdBy(){
        return $this->belongsTo('App\User','user_id','id');
      }
    public static function datasetOperations(){

    	return [
    				'new'		=>	'Add New',
    				'replace'	=>	'Reaplce with old one',
    				'append'	=>	'Append old dataset'
    		   ];
    }

    static function countDataset()
    {
            return self::count();
    }
}
