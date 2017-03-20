<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class organization extends Model
{
    protected $fillable = ['activation_code','organization_name','created_by'];
    protected $table = 'organization';

    public static function org_list()
    {
    	return self::orderBy('id')->pluck('organization_name','id');
    }

    public function users()
    {
    	return $this->hasMany('App/organization','organization_id','id');
    }
}
