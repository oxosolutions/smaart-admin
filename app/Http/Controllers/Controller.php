<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\LogSystem as LOG;
use Carbon\Carbon AS TM;



class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
   
    
    
  public function putInLog($ip, $user_id, $email, $name, $query , $value, $time)
  {
        $Lg             =   new LOG;
        $Lg->user_id    =   $user_id;
        $Lg->type       =   "model";            
        $Lg->text       =   json_encode(['query'=>$query ,'email'=>$email ,'name'=>$name,'value'=>$value ,'time'=> $time]);
        $Lg->ip_address =   $ip;
        $Lg->save(); 
  }


    public function __destruct()
    {	
        $user = Auth::user();
        $uid = $user->id; 
        $email =$user->email;
        $name =$user->name;

        foreach (DB::getQueryLog() as $key => $value){ 
			if(str_contains($value['query'], 'log_systems') ==true || str_contains($value['query'], 'count(*)')==true){

   				 }else{
                $log    = LOG::orderBy('id','desc')->where('user_id',$uid)->first();
                $logAr  = json_decode($log->text,true);
                $insertTime = $log->created_at;
                $currentTime = TM::now();
                $addSecond = $insertTime->addSeconds(10);
                if(array_key_exists('query', $logAr))
                {
                  if($addSecond > $currentTime  && $logAr['query'] == $value['query'])
                  {
                  // dump('not insert log forthis');
                  }else{

                $this->putInLog($this->ipAdress, $uid, $email, $name, $value['query'], $value['bindings'], $value['time'] );
                  
                  }
                }else{
                    $this->putInLog($this->ipAdress, $uid, $email,$name, $value['query'], $value['bindings'], $value['time'] );
                }
          }

        }
    }
   } 


