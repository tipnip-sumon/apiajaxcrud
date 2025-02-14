<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return view('login');
});
Route::post('signup',[AuthController::class,'signup']);
Route::post('login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout',[AuthController::class,'logout']);
    Route::apiResource('posts',PostController::class);
});
// Route::apiResource('posts',PostController::class)->middleware('can:update');
// Route::apiResource('posts',PostController::class)->middleware('can:update');