<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function allpost(){
        return view('allpost');
    }
    public function addpost(){
        return view('addpost');
    }
}
