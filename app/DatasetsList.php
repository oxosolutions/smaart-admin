<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasetsList extends Model
{
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
