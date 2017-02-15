<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneratedVisualQuerie extends Model
{
    protected $fillable = ['visual_id','query','query_result'];

    public function visualId(){

    	return $this->belongsTo('App\GeneratedVisual','visual_id','id');
    }

    function createdBy(){

    	return $this->belongsTo('App\User','created_by','id');
    }
}
