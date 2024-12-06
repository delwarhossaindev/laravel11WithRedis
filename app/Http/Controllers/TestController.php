<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    //
    public function test(Request $request)
    {
          dd(Auth::user()->id,auth()->user(),'okay');
          
    }
}
