<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Session;
use Auth;

class SurveySetting extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $softDelete = true;
	protected $fillable = ['survey_id','key','value'];
	Public function __construct()
	{
		parent::__construct();
		if(Session::get('org_id')==null)
		{
			 $this->table = Auth::user()->organization_id."_survey_settings";
		}else{
			 $this->table = Session::get('org_id')."_survey_settings";

		}
	}

    
}
