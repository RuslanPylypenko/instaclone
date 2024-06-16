<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
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
            new Middleware('auth:sanctum', except: ['reset']),
        ];
    }

    //TODO fix this logic
    public function request(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->resetPassword->request($user);

        return response()->json([
            'message' => 'Check your email to continue...',
        ]);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $this->resetPassword->reset($request->token, $request->password);

        return response()->json([
            'message' => 'Password was changed',
        ]);
    }
}
