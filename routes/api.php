<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function ($router) {
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('confirm/{token}', [RegisterController::class, 'confirm'])->name('register.confirm');

    Route::post('reset-password-request', [ResetPasswordController::class, 'request']);
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');

    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::group([
    'middleware' => ['auth:confirmed'],
], function ($router) {

    Route::prefix('posts')->group(function () {
        Route::post('/{post}/like', [PostController::class, 'addLike'])->name('posts.like');
        Route::post('/', [PostController::class, 'createPost'])->name('posts.store');
        Route::patch('/{token}', [PostController::class, 'updatePost'])->name('posts.update');
        Route::get('/{token}', [PostController::class, 'show'])->name('posts.show');
        Route::delete('/delete/{id}', [PostController::class, 'deletePost'])->name('posts.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('/users/{userId}/posts', [PostController::class, 'userPosts'])->name('users.show.posts');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    });
});
