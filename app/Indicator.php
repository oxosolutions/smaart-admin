<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Indicator extends Model
{
    protected $fillable = ['indicator_title', 'targets_id','created_by'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    function targets(){
    	return $this->belongsTo('App\GoalsTarget');
    }

    function createdBy(){
    	return $this->belongsTo('App\User','created_by','id');
    }

    static function indicatorCount()
    {
    	return self::count();
    }


}
