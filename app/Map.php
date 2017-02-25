<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class Map extends Model
{

	 public function __construct(){
      parent::__construct();
      if(Session::get('org_id') == null){
        foreach(Auth::user()->meta as $key => $value){
            if($value->key == 'organization'){
                $this->table = $value->value.'_maps';
                break;
            }
        }
      }else{
        $this->table = Session::get('org_id').'_maps';
      }
     
   }
    //
     protected $fillable = [ 'code', 'code_albha_2', 'code_albha_3', 'code_numeric', 'parent', 'title', 'description', 'map_data'];

     public static function getParent()
     {
     		return self::pluck('title','id');	
     }
}
