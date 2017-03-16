<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;

class SurveyQuestion extends Model
{
    use SoftDeletes;
   	protected $fillable = [ 'survey_id', 'answer', 'question'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    public function __construct()
    {
    	parent::__construct();
      if(Session::get('org_id') == null){
          $this->table = Auth::user()->organization_id.'_survey_questions';
      }else{
        $this->table = Session::get('org_id').'_survey_questions';
      }
   }
}
