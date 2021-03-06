<?php

namespace App\Http\Controllers\Services;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DatasetsList as DL;
use App\Visualisation as VIZ;
use App\Surrvey as SR;
use App\User as US;
use App\UserMeta as UM;
use App\organization as ORG;

	class DashboardController extends Controller
	{

		public function DashboardData()
		{
			$org_id = Auth::user()->organization_id;
			$pp = UM::select('value')->where(['user_id' => Auth::user()->id , 'key' => 'profile_pic'])->get();
			$profile_pic = "";
				foreach ($pp as $key => $value) {
					$profile_pic = asset('profile_pic/'.$value->value);
				}
			
				$data['dataset_count'] = count(DL::all());
				$data['dataset_list'] = DL::select('id','dataset_name')->orderBy('id','DESC')->limit('5')->get();
				$data['visual_count'] = count(VIZ::all());
				$data['visual_list'] = VIZ::select('id','name')->orderBy('id','DESC')->limit('5')->get();
				$data['survey_count'] = count(SR::all());
				$data['survey_list'] = SR::select('id','name')->orderBy('id','DESC')->limit('5')->get();
						
			

				$data['organization_detail'] = ORG::where('id',$org_id)->first();
				$data['user_profile'] =  US::where('id', Auth::user()->id)->get();
				$data['user_meta'] =  	[
									'profile_pic' => $profile_pic,
									'phone' => UM::where(['user_id' => Auth::user()->id , 'key' => 'phone'])->get(),
									'address' => UM::where(['user_id' => Auth::user()->id , 'key' => 'address'])->get()
								];
								

			$data['user_count'] = 0;
			$data['user_list'] = array();
			if(Auth::user()->role_id == 1){
				$data['user_count'] = US::where(['organization_id'=>$org_id, 'role_id'=>2 ])->count();
				$data['user_list'] = US::select('id','name','approved')->where(['organization_id'=>$org_id, 'role_id'=>2 ])->orderBy('id','DESC')->limit('5')->get();
			}elseif(Auth::user()->role_id == 3){
				$data['user_count'] = US::get()->count();
				$data['user_list'] = US::select('id','name','approved')->whereNotIn('role_id',[3])->orderBy('id','DESC')->limit('5')->get();
			}
			return $data;
		}
	}