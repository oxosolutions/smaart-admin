<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiUsers extends Model
{
    protected $fillable = ['username','password','email','token'];

    
}
