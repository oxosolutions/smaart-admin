<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable =['id', 'name', 'display_name', 'description'];

    protected $dates = ['deleted_at'];
    protected $softDelete = true;
    
	public static function role_list()
	{
		$result = 	self::orderBy('id')->pluck('name','id');
		return $result;
	}

	public function users()
	{
		return $this->hasMany('App\User', 'role_id', 'id');
	}

	public function permisson()
	{
		return $this->hasMany('App\PermissonRole','role_id','id');
	}

	public static function rolePermisson()
	{		
			$role_id = Auth::user()->role_id;
			return self::find($role_id);
	}



}
