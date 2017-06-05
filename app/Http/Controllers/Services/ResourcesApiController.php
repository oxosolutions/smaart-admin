<?php

namespace App\Http\Controllers\Services;

use App\GoalsResource as RES;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResourcesApiController extends Controller
{
        public function ResourcesList()
        {
            $data = RES::all();
            if (count($data) < 1){
                return ['status' => 'error' ,'records' => 'no record found'];
            }else{
                return ['status' => 'success' , 'records' => $data];
            }

        }
}
