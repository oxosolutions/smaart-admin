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
   
    // public function __construct(Request $request)
    // {
    // 	 // $this->ipAdress = 123124;  //$request->ip();

    //   //   DB::enableQueryLog();     
    // }

    public function __destruct()
    {			

    	$user = Auth::user(); 
        $uid=  $user->id;  

        foreach (DB::getQueryLog() as $key => $value){ 
        		if(str_contains($value['query'], 'log_systems') ==true || str_contains($value['query'], 'count(*)')==true)
				{//NOT PUT LOG SYSTEM QUERY && COUNT
				}	
				else{
						$log    = LOG::orderBy('id','desc')->where('user_id',$uid)->first();
		                $logAr  = json_decode($log->text,true);
		                $insertTime = $log->created_at;
		                $currentTime = TM::now();
		                $addSecond = $insertTime->addSeconds(10);
		                 if(array_key_exists('query', $logAr))
          	       			{
          	       				 if($addSecond > $currentTime  && $logAr['query'] == $value['query'])
                  					{}
                  					else{
									$Lg             =   new LOG;
									$Lg->user_id    =   $uid;
									$Lg->type       =   "model";            
									$Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time'],'email'=>$user->email]);
									$Lg->ip_address =   $this->ipAdress;
									$Lg->save(); 

                  					}

          	       			}else{
          	       				$Lg             =   new LOG;
									$Lg->user_id    =   $uid;
									$Lg->type       =   "model";            
									$Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time'],'email'=>$user->email]);
									$Lg->ip_address =   $this->ipAdress;
									$Lg->save(); 

          	       			}

        			
                }
        }
    }

          // if($value['query'] =="insert into `log_systems` (`user_id`, `type`, `text`, `ip_address`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)" || $value['query'] =="select * from `log_systems` where `user_id` = ? order by `id` desc limit 1" || $value['query']=="select * from `users` where `users`.`id` = ? limit 1" ||  str_contains($value['query'], 'count(*)')==true)
          // {  //Not put in log
          // }else{
          //       $log    = LOG::orderBy('id','desc')->where('user_id',$uid)->first();
          //       $logAr  = json_decode($log->text,true);
          //       $insertTime = $log->created_at;
          //       $currentTime = TM::now();
          //       $addSecond = $insertTime->addSeconds(10);
          //       if(array_key_exists('query', $logAr))
          //       {
          //         if($addSecond > $currentTime  && $logAr['query'] == $value['query'])
          //         {
          //         // dump('not insert log forthis');
          //         }else{
          //           $Lg             =   new LOG;
          //           $Lg->user_id    =   $uid;
          //           $Lg->type       =   "model";            
          //           $Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time'],'email'=>$user->email]);
          //           $Lg->ip_address =   $this->ipAdress;
          //           $Lg->save(); 
          //         }
          //       }else{
          //           $Lg             =   new LOG;
          //           $Lg->user_id    =   $uid;
          //           $Lg->type       =   "model";            
          //           $Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time'],'email'=>$user->email]);
          //           $Lg->ip_address =   $this->ipAdress;
          //           $Lg->save(); 
          //       }
          // }
        //}   

  					// $Lg             =   new LOG;
       //              $Lg->user_id    =   Auth::user()->id;
       //              $Lg->type       =   "model";            
       //              $Lg->text       =   json_encode(['query'=>"query" , 'value'=>'bind' ,'time'=> '13']);
       //              $Lg->ip_address =   $this->ipAdress;
       //              $Lg->save(); 
                   
    //}


		
}
