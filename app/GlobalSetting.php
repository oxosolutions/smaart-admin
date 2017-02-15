<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $fillable = ['meta_key','meta_value','updated_by'];

    public function user(){

    	return $this->belongsTo('App\User','updated_by','id');
    }
}
