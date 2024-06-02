<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\User\ResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ResetPasswordController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly ResetPassword $resetPassword,
    ) {
    }

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['reset'])
        ];
    }

    public function request(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->resetPassword->request($user);
        return response()->json([
            'message' => 'Check your email to continue...'
        ]);
    }

    public function reset(): JsonResponse
    {
        return response()->json([
            'message' => 'Password was changed'
        ]);
    }
}
