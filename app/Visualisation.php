<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Visualisation extends Model
{
    use SoftDeletes;
    protected $fillable = ['dataset_id','visual_name','options','settings','created_by'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    function createdBy(){
    	return $this->belongsTo('App\User','created_by','id');
    }

    public function dataset(){
    	return $this->belongsTo('App\DatasetsList');
    }

    static function visualisationCount()
    {
        return self::count();
    }


}
