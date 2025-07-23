<?php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TestController extends Controller
{
    public function index()
    {
        dd('This is a test route! sadasdasdtest 01 |||| AAAAA base_path(): ' . base_path());
    }
}
