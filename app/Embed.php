<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embed extends Model
{
    protected $fillables = ['visual_id','org_id','user_id','embed_token'];
}
