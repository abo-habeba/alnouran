<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RestBalanceController;
use App\Http\Controllers\RestallowanceController;
use App\Http\Controllers\RegularBalanceController;


Route::prefix('/php')->group(function () {
    Route::get('/seed', function () {
        exec('php artisan seed', $output, $exitCode);
    if ($exitCode === 0) {
        return 'تم تنفيذ الأمر بنجاح.';
    } else {
        return 'حدث خطأ أثناء تنفيذ الأمر: ' . implode(PHP_EOL, $output);
    }
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/reports', ReportController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/Comments', CommentController::class);
    Route::apiResource('/absence', AbsenceController::class);
    Route::apiResource('/regular', RegularBalanceController::class);
    Route::apiResource('/rest', RestBalanceController::class);
    Route::apiResource('/restallowance', RestallowanceController::class);
    ////////
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/{userID}/reports', [UserController::class, 'getUserReports']);
    Route::get('/check', [UserController::class, 'authCheck']);
});

Route::apiResource('/Stations', StationController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
