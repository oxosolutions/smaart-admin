<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyEmbed extends Model
{
    protected  $fillable = ['survey_id', 'user_id', 'org_id', 'embed_token'];
}
