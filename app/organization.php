<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class organization extends Model
{
    protected $fillable = ['organization_name','created_by'];
    protected $table = 'organization';
}
