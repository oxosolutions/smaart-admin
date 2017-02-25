<?php

  namespace App;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\SoftDeletes;
  use Session;
  use Auth;
  class Page extends Model
  {
    /*public function __construct(){
      parent::__construct();
      if(Session::get('org_id') == null){
        foreach(Auth::user()->meta as $key => $value){
            if($value->key == 'organization'){
                $this->table = $value->value.'_pages';
                break;
            }
        }
      }else{
        $this->table = Session::get('org_id').'_pages';
      }
     
   }*/
      use SoftDeletes;
      protected $fillable = ['page_title','content','page_image','status','created_by','page_slug','page_subtitle'];
      protected $dates = ['deleted_at'];
      protected $softDelete = true;

      public function createdBy(){
        return $this->belongsTo('App\User','created_by','id');
      }

      public static function statusList(){
          return [
                  '0' => 'Not Published',
                  '1' => 'Publish',
                  '2' => 'Protected'
                ];
      }

      static function countPage()
      {

        return self::count();
      }
  }
