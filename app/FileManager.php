<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    protected $fillable =['name', 'type', 'size', 'server_path', 'url','slug', 'modified_at', 'permission'];
    
}
