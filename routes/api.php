<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::apiResource('/Stations',StationController::class);
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/posts', PostController::class);
    Route::apiResource('/users', UserController::class);
    Route::get('/check',[UserController::class,'authCheck']);
});
Route::apiResource('/Comments', CommentController::class);
Route::post('/logout/{id}',[AuthController::class,'logout']);

Route::get('/user/{userID}/posts',[UserController::class,'getUserPosts']);

Route::post('/login',[AuthController::class,'login']);
Route::apiResource('/Stations',StationController::class);
Route::post('/register',[UserController::class,'store']);