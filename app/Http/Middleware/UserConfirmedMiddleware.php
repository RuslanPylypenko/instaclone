<?php

namespace App\Http\Middleware;

use App\Models\User\UserEntity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserConfirmedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var UserEntity $user */
        $user = $request->user();

        if ($user->isWait()) {
            return response()->json(['message' => 'Your account is waiting confirmation.'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
