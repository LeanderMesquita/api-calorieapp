<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EntrieController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {

    Route::apiResource('users', UserController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    Route::apiResource('meals', MealController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::apiResource('entries', EntrieController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::prefix('admin')->group(function () {
        Route::get('/report', [AdminController::class, 'report']);
    });
});
