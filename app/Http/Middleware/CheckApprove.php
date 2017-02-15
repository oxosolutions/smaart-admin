<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class CheckApprove
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
        if(Auth::user()->approved==0)
            {
                Session::flash('error','Admin not approve yet. please wait for approval!');
                Auth::logout();
                return redirect('login');
            }
        return $next($request);
    }
}