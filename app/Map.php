<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class Map extends Model
{

	 public function __construct(){
      parent::__construct();
      $user = Auth::user();
       if($user->role_id ==2 || $user->role_id ==1 )
       {
          $this->table = $user->organization_id.'_maps';
        }        
     
   }
     protected $fillable = [ 'code', 'code_albha_2', 'code_albha_3', 'code_numeric', 'parent', 'title', 'description', 'map_data'];

     public static function getParent()
     {
     		return self::pluck('title','id');	
     }
}
