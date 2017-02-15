<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissonRouteMapping extends Model
{
    //
	
    protected $fillable = ['permisson_id','route','route_for'];

    public function permisson()
    {
    	return $this->belongsTo('App\Permisson');
    }
}
