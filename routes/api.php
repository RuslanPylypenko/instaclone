<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);

    Route::post('logout',[AuthController::class,'logout'])
        ->middleware('auth:sanctum');
    Route::post('me', [AuthController::class, 'me'])
        ->middleware('auth:sanctum');
});

