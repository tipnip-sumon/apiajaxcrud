<?php

use Illuminate\Support\Facades\Route;

Route::get('/allposts', function () {
    return view('allposts');
});
Route::get('/addpost', function () {
    return view('addpost');
});
