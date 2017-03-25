<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    protected $fillable =['name', 'type', 'size', 'server_path', 'url', 'modified_at', 'permission'];
    
}
