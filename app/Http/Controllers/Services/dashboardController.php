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

	class DashboardController extends Controller
	{

		public function DashboardData()
		{
			$pp = UM::select('value')->where(['user_id' => Auth::user()->id , 'key' => 'profile_pic'])->get();
			$profile_pic = "";
				foreach ($pp as $key => $value) {
					$profile_pic = asset('profile_pic/'.$value->value);
				}
			$data = array(
							'dataset_count' => count(DL::all()),
							'dataset_list' => DL::select('id','dataset_name')->orderBy('id','DESC')->limit('5')->get(),

							'visual_count' => count(VIZ::all()),
							'visual_list' => VIZ::select('id','visual_name')->orderBy('id','DESC')->limit('5')->get(),

							'survey_count' => count(SR::all()),
							'survey_list' => SR::select('id','name')->orderBy('id','DESC')->limit('5')->get(),

							'user_count' => count(US::all()),
							'user_list' => US::select('id','name')->orderBy('id','DESC')->limit('5')->get(),

							'user_profile' =>  US::where('id', Auth::user()->id)->get(),
							'user_meta' =>  	[
									'profile_pic' => $profile_pic,
									'phone' => UM::where(['user_id' => Auth::user()->id , 'key' => 'phone'])->get(),
									'address' => UM::where(['user_id' => Auth::user()->id , 'key' => 'address'])->get(),
								]
						);
			return $data;
		}
	}