<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogSystem AS LOG;
use App\User;
use Carbon\Carbon as TM;
use Auth;
/*use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;*/


class LogsystemController extends Controller
{
    //


	public function CheckForLog()
	{
			$cTime = TM::now();
			$cTime->subSeconds(30);
			$logs = LOG::orderBy('id','desc')->where('created_at','>',$cTime)->where('user_id',Auth::user()->id)->limit(10)->get();
			
			if(count($logs)>0)
			{
						echo "have log";
		 foreach ($logs as $key => $log) {
					 $logAr  = json_decode($log->text,true);
			if(array_key_exists('query', $logAr))
          	   {

          	   	dump($logAr['query']);

					// if($logAr['query'] != $value['query'])
					// {

					// 			$logAr['query'];

					// }else{

					// }
				}
		}

			}
			
			else{
				echo "not log";
			}
	}
    public function viewLog()
    {

//  $a = array(array('a'=>1), array('b'=>4));
//  echo "<br>";
//  $result = array_reduce($a, 'array_merge', array());
// print_r($result);
//  die;
	//$uName = User::where('id',$id)->first()->name; 

    	$log = LOG::orderBy('id','desc')->paginate(30);

    	$plugins = ['log'=>$log];
    	return view('log.index',$plugins);
    }

    public function search_log(Request $request)
    {
    	
    	
    	if($request->from && $request->to && $request->user_name)
			{
				$from = TM::parse($request->from)->format('Y-m-d');

				$uName = User::where('id',$request->user_name)->first()->name; 
				$log = LOG::orderBy('id','desc')->whereBetween('created_at', [$request->from, $request->to])->Where('user_id',$request->user_name)->paginate(30);

			}
    		else if($request->from && $request->to )
    		{
				$from = TM::parse($request->from)->format('Y-m-d'); 
				$to = TM::parse($request->to)->format('Y-m-d');				
    			$log = LOG::orderBy('id','desc')->whereBetween('created_at', [$from, $to])->paginate(30);
    		}
    		else if($request->from)
    		{
    			$from = TM::parse($request->from)->format('y-m-d');
				$log =	LOG::orderBy('id','desc')->whereDate('created_at', '=', $from)->paginate(30);
    		}else if($request->user_name && $request->from)
    		{
    			$from = TM::parse($request->from)->format('y-m-d');
				$log =	LOG::orderBy('id','desc')->whereDate('created_at', '>=', $from)
												->Where('user_id',$request->user_name)
												->paginate(30);
    		}
			else if($request->user_name)
    		{
    			$log = LOG::orderBy('id','desc')->Where('user_id',$request->user_name)->paginate(30);
    		}
    		else{
    			$log = LOG::orderBy('id','desc')->paginate(30);
    		}


			$plugins = ['log'=>$log];
    	return view('log.index',$plugins);
    		
    }
}
