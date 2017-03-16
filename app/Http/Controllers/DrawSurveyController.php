<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\Surrvey;
use App\SurveyQuestion as SQ;
use Auth;
use App\UserMeta as um;
use DB;
use App\SurveyQuestionGroup as GROUP;
use App\SurveySetting as SSETTING;

class DrawSurveyController extends Controller
{
    
    public function draw_survey($id)
    {
    	$sdata = Surrvey::findORfail($id);
    	return view('survey.draw_survey',['sdata'=>$sdata]);
    }

    public function survey_store(Request $request)
    {
    	dd($request->all());
    }
}
