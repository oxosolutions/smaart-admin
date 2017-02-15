<?php

namespace App\Http\Middleware;

use Closure;
use App\LogSystem as LG;
use Auth;
Use DB;
use Carbon\Carbon AS TM;
use Session;

class Logsystem
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
       $action = $request->route()->getAction();
       if (Auth::check())
        {
            $user = Auth::user();
            $current_url =  \Route::current()->uri(); 
            $user_id     =  $user->id; 
            $ip          =  $request->ip();
            try{
                $chk = LG::orderBy('id','desc')->where('user_id',$user_id)->first();
                $text = json_decode($chk->text,true);
                $mytime = TM::now();
                $insertTime = $chk->created_at;
                $addSecond = $insertTime->addSeconds(30);
                if($addSecond < $mytime && $current_url ==  $text['route'])
                {
                   $this->createLog($current_url , $ip ,$user_id , $user->email, $user->name,$action);
                                
                }else if($current_url !=  $text['route'])
                    {
                        $this->createLog($current_url, $ip,$user_id);
                    }   
            }catch(\Exception $e)
            {
                $this->createLog($current_url , $ip, $user_id, $user->email, $user->name  ,$action);
            }
        }
        return $next($request);
    }
        Public function createLog($url ,$ip, $uid,$email, $name,$action )
        {
            $Lg = new LG();
            $Lg->user_id = $uid;
            $Lg->type ="frontend";
            if(array_key_exists('route_name', $action))
            {
                $Lg->route_name = $action['route_name'];
            }
            $Lg->text =json_encode(['route'=>$url,'email'=>$email, 'name'=>$name]);
            $Lg->ip_address =  $ip;
            $Lg->save();                      
        }
}
