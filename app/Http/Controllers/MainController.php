<?php

namespace App\Http\Controllers;

use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\Request;

class MainController extends Controller
{
    // 웹 최초 진입시 처리
    public function index(Request $request){
        return view('index');
    }

}
