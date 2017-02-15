<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserMeta as um;
use DB;
use Carbon\Carbon;


class userMeta extends Controller
{
	public function allData(Request $request)
	{	
		print_r("hello");
		exit();
		$data = $request->name;
	}    	
}
