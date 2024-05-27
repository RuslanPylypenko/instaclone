<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/posts', [PostController::class, 'createPost'])->name('posts.store');
Route::get('/posts/{token}', [PostController::class, 'show'])->name('posts.show');

Route::get('/users/{userId}/posts', [PostController::class, 'userPosts'])->name('users.show.posts');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
