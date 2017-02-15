<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LogSystem AS LOG;
use Carbon\Carbon as TM;
use Auth;

class LogApiController extends Controller
{
 
	public function logActivity()
	{
		$id = Auth::user()->id;
		$log = LOG::orderBy('id','desc')->Where('user_id',$id )->whereNotNull('route_name')->get();
		return ['log'=>$log];
	}
	

}
