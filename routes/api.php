<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/Stations',StationController::class);
    Route::apiResource('/tasks', TaskController::class);
});
Route::post('/logout/{id}',[AuthController::class,'logout']);
Route::apiResource('/users', UserController::class);

Route::post('/login',[AuthController::class,'login']);
Route::post('/Stations',[StationController::class,'store']);
Route::post('/register',[UserController::class,'store']);