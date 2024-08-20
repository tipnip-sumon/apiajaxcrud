<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

// Route::get('/allposts', function () {
//     return view('allposts');
// });
// Route::get('/addpost', function () {
//     return view('addpost');
// });

Route::get('/allpost',[ViewController::class,'allpost']);
Route::get('/addpost',[ViewController::class,'addpost']);
