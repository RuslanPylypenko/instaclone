<?php

use App\Http\Middleware\CheckTokenExpiry;
use App\Http\Middleware\UserConfirmedMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('auth:confirmed', [
            'auth:sanctum',
            UserConfirmedMiddleware::class,
        ]);
        // $middleware->append(UserConfirmedMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        });
    })->create();
