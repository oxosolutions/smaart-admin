<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Surrvey extends Model
{
   use SoftDeletes;
   protected $fillable = [ 'name', 'created_by', 'description'];
   protected $dates = ['deleted_at'];
   protected $softDelete = true;

   public static function getSurrvey()
   {
   		return self::pluck('name','id');
   }

   public function questions()
   {
   		return $this->hasMany('App\SurrveyQuestion','surrvey_id','id');
   }
}
