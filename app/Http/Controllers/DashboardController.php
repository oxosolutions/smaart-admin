<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RegisterNewUser;
use Illuminate\Support\Facades\Mail;
use Auth;
use Session;
use App\Surrvey;
class DashboardController extends Controller
{

    public function index(){
    	if(Auth::user()->approved == 0){
    		return redirect('not-approved');
    	}
		 foreach (Auth::user()->meta as $key => $value) {
		  		if($value->key =="organization")
		  		{
		  			
				}
                Session::put('org_id', Auth::user()->organization_id);

             

		  } 
		
    	$plugins = [

    	];
    	return view('dashboard.index', $plugins);
    }
}
