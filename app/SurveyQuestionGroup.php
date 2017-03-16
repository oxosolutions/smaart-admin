<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Session;

class SurveyQuestionGroup extends Model
{	
	use SoftDeletes;
	protected $dates =['deleted_at'];
	protected $SoftDelete =True;
	protected $fillable = ['survey_id', 'title'];
    public function __construct()
    {
    	parent::__construct();
      if(Session::get('org_id') == null){
          $this->table = Auth::user()->organization_id.'_survey_question_groups';
      }else{
        $this->table = Session::get('org_id').'_survey_question_groups';
      }
    }

    public function question()
    {
    	return $this->hasMany('App\SurveyQuestion','group_id','id');
    }
}
