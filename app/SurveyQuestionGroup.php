<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Session;

class SurveyQuestionGroup extends Model
{	
	use SoftDeletes;
  public static $question_take = null;
  public static $question_random = null;
	protected $dates =['deleted_at'];
	protected $SoftDelete =True;
	protected $fillable = ['survey_id', 'title','group_order','description'];
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
      $question = $this->hasMany('App\SurveyQuestion','group_id','id')->take(self::$question_take);
      if(self::$question_random!=null)
      {
        $question->orderByRaw("RAND()");
      }
      return $question;
    }
}
