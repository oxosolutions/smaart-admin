<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    //
     protected $fillable = [ 'code', 'code_albha_2', 'code_albha_3', 'code_numeric', 'parent', 'title', 'description', 'map_data'];

     public static function getParent()
     {
     		return self::pluck('title','id');	
     }
}
