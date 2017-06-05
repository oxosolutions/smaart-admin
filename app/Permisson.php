<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Route;

class Permisson extends Model
{
    use SoftDeletes;

    protected $fillable =['id', 'name', 'icon'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    public static function permisson_data()
    {
    	$data =	self::orderBy('id')->get();
        return $data;
    }

    public static function getRouteListArray()
    {

            $routes = Route::getRoutes();
            foreach($routes as $route)
            {
               if(substr($route->getPath() ,0,1)=='_'){
                }
               else{
                    $rout =  str_replace('/{id}','',$route->getPath());
                    $routeList[$rout] = $rout;
                }
            }

            return $routeList;
    }

    public static function getRouteFor()
    {
        $routeFor['read']   =   "List";
        $routeFor['write']  =   "Create";
        $routeFor['delete'] =   "Delete";
        $routeFor['other'] =   "Other";
        return $routeFor;

    }

    public function routeMapping()
    {
        return $this->hasMany('App\PermissonRouteMapping','permisson_id','id');
    }

    
    public function permisson()
    {
        $this->hasMany('App\PermissonRole','permisson_id','id');
    }

    public static function allRoute()
    {
        return self::all();
    }
    


}
