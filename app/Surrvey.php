<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;
class Surrvey extends Model
{
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
}
