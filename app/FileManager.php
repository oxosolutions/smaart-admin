<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    protected $fillable =['name', 'type', 'size', 'server_path', 'url','media', 'modified_at', 'permission','org_id'];
    
}
