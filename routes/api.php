<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('confirm/{token}', [RegisterController::class, 'confirm'])->name('register.confirm');

    Route::post('reset-password-request', [ResetPasswordController::class, 'request']);
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');

    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout',[LoginController::class,'logout']);
});

Route::group([
    'middleware' => 'auth:confirmed',
], function ($router) {
    Route::post('/posts', [PostController::class, 'createPost'])->name('posts.store');
    Route::get('/posts/{token}', [PostController::class, 'show'])->name('posts.show');

    Route::get('/users/{userId}/posts', [PostController::class, 'userPosts'])->name('users.show.posts');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
});

