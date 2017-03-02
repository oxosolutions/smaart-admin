<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class Usermap extends Model
{

	 public function __construct(){
      parent::__construct();
      if(Session::get('org_id') == null){
            $this->table = Auth::user()->organization_id.'_usermaps';        
      }else{
        $this->table = Session::get('org_id').'_maps';
      }
     
   }
     protected $fillable = [ 'code', 'code_albha_2', 'code_albha_3', 'code_numeric', 'parent', 'title', 'description', 'map_data'];

     public static function getParent()
     {
     		return self::pluck('title','id');	
     }
}
