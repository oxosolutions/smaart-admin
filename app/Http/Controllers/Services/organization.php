<?php

namespace App\Http\Controllers\Services;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\organization as ORG;
use Session;

class organization extends Controller
{
	public function store(Request $request)
	{
		$data = array('organization_name' => $request->organization_name );	
		$inserted = ORG::create($data);
		if ($inserted){
			Session::flash('success','Successfully created!');
			return view('organization.create');
		}else{
			Session::flash('error','Some thing goes wrong Try again!');
		}
	}
	public function allOrganization()
	{
		$data = ORG::all();
		$responceArray = [];
		$index = 0;

		foreach ($data as $key => $value) {
			$responceArray[$index]['id']=$value['id'];
			$responceArray[$index]['org_name']=$value['organization_name'];
			$responceArray[$index]['created_at'] = $value->created_at->format('Y-m-d H:i:s');
			$index++;
		}
		return ['status' => "success",'records' => $responceArray];
	}

}
