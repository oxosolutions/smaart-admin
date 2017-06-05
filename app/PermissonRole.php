<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissonRole extends Model
{
    //
        protected $fillable =['id', 'role_id', 'permisson_id', 'read','write','other'];
        protected $table = 'permisson_roles';
    
    public function permissons(){
    	return $this->belongsTo('App\Permisson','permisson_id','id');
    }

    

}
