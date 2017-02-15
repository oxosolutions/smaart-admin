<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DatasetsList as DL;
use App\Visualisation as VIZ;
use App\Surrvey as SR;

	class DashboardController extends Controller
	{
		public function DashboardData()
		{
			$data = array(
							'dataset_count' => count(DL::all()),
							'dataset_list' => DL::select('id','dataset_name')->orderBy('id','DESC')->limit('5')->get(),

							'visual_count' => count(VIZ::all()),
							'visual_list' => VIZ::select('id','visual_name')->orderBy('id','DESC')->limit('5')->get(),

							'survey_count' => count(SR::all()),
							'survey_list' => SR::select('id','name')->orderBy('id','DESC')->limit('5')->get()
						);
			return $data;
		}
	}
