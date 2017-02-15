<?php

  namespace App;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\SoftDeletes;
  class Page extends Model
  {
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
