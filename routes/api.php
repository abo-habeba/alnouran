<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\Auth\AuthController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/posts', PostController::class);
    Route::apiResource('/reports', ReportController::class);
    Route::apiResource('/users', UserController::class);
    Route::get('/check', [UserController::class, 'authCheck']);
    Route::apiResource('/Comments', CommentController::class);
});
Route::apiResource('/Stations', StationController::class);
Route::post('/logout/{id}', [AuthController::class, 'logout']);
Route::get('/user/{userID}/reports', [UserController::class, 'getUserReports']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);