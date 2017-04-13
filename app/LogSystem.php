<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LogSystem extends Model
{   
	protected $fillable = ['user_id'];
	protected $appends = ['created_at_for_humans'];

	public function getCreatedAtForHumansAttribute(){
	return Carbon::parse($this->attributes['created_at'])->diffForHumans();
}
     
}
