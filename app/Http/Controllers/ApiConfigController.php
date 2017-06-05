<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiConfigController extends Controller
{
    function index(){

    	$plugin = [
    				'js'=>['custom'=>['api-config']]
    			];
    	return view('api/index',$plugin);
    }
}
