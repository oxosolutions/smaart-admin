<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class GMap extends Model
{

	   protected $table = 'maps';
     protected $fillable = [ 'code', 'code_albha_2', 'code_albha_3', 'code_numeric', 'parent', 'title', 'description', 'map_data'];

     public static function getParent()
     {
     		return self::pluck('title','id');	
     }
}
