<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRoute extends Controller
{
    public function getRoutes()
    {
      return View::make('roles.index');
    }
}
