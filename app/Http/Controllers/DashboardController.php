<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RegisterNewUser;
use Illuminate\Support\Facades\Mail;
use Auth;
class DashboardController extends Controller
{

    public function index(){

    	if(Auth::user()->approved == 0){
    		return redirect('not-approved');
    	}
    	$plugins = [

    	];
    	return view('dashboard.index', $plugins);
    }
}
