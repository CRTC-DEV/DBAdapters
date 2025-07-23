<?php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TestController extends Controller
{
    public function index()
    {
        dd('This is a test route! Last test');
    }
}
