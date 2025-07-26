<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
}