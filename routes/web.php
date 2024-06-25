<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/debug-sentry', function () {
    throw new \App\Exceptions\ApiException('My first Sentry error!', 500);
});
