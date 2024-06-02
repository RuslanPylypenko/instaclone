<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('confirm/{token}', [RegisterController::class, 'confirm'])->name('register.confirm');

    Route::post('reset-password-request', [ResetPasswordController::class, 'request']);
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');

    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout',[LoginController::class,'logout']);
});

