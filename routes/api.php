<?php

use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::post('/users', [UserController::class, 'store']);

Route::middleware('auth:api')->group(function () {

    Route::resource('/users', UserController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    Route::apiResource('meals', MealController::class)
        ->only(['index', 'store','show', 'update', 'destroy']);
});
