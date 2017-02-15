<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\SoftDeletes;


class SurrveyQuestion extends Model
{
    use SoftDeletes;
   	protected $fillable = [ 'surrvey_id', 'answer', 'question'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
}
