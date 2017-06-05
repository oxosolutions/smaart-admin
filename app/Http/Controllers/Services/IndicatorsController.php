<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Indicator as IC;

class IndicatorsController extends Controller
{
    function indicators(){
    	return IC::all();
    }
}
