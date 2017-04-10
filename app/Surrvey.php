<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;
class Surrvey extends Model
{
   public static $group_take = null;
   public static $group_random = null;
   protected $table;
   public function __construct(){
      parent::__construct();
      if(Session::get('org_id') == null){
          $this->table = Auth::user()->organization_id.'_surveys';
      }else{
        $this->table = Session::get('org_id').'_surveys';
      }
     
   }
    
   use SoftDeletes; 
   
   protected $fillable =    ['name', 'created_by', 'description'];
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
   public function setting()
   {
      return $this->hasMany('App\SurveySetting','survey_id','id');
   }
   public function group()
   { 
      $group =  $this->hasMany('App\SurveyQuestionGroup','survey_id','id')->take(self::$group_take);
      if(self::$group_random!=null)
      {
      $group->orderByRaw("RAND()");
      }
      return $group;
   }

   public function creat_by()
   {
      return $this->belongsTo('App\User','created_by','id')->select(['name','role_id']);
   }
}
