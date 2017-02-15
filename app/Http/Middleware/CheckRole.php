<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\Role;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */


    public function handle($request, Closure $next)
    {
       
        //get current route
        $current_route =  $request->route()->getUri();

        // get Role permisson
        $role_id = $request->user()->role_id;
        $role = DB::table('roles as r')
                    ->select('prm.*','r.id as rid','r.name as rname','pr.read','pr.write','pr.delete','p.name as pname','p.id as pid')
                    ->leftJoin('permisson_roles as pr','pr.role_id','=','r.id')
                    ->leftJoin('permissons as p','p.id','=','pr.permisson_id')
                    ->leftJoin('permisson_route_mappings as prm','prm.permisson_id','=','p.id')
                    ->where('r.id',$role_id)->get();
              //dd($role);         
        foreach ($role as  $value) {
            # code...
            if($value->route== $current_route)
            {
                //echo $value->read;
                 $routePermisson = $value->route_for;
                if($value->$routePermisson==true)
                {
                 return $next($request);
                }
            }
        }

         return response([
                        'error' => [
                            'code' => 'INSUFFICIENT_ROLE',
                            'description' => 'You are not authorized to access this resource.'
                        ]
                    ], 401);

    }
    private function getRequiredRoleForRoute($route)
    {
        $actions = $route->getAction();
        return $actions;
    }
}
