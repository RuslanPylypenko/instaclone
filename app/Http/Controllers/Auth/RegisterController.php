<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\UseCases\User\SignUp;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RegisterController extends Controller implements HasMiddleware
{
    public function __construct(
        private SignUp $signUp,
    ){
    }

    public static function middleware()
    {
        return [
            new Middleware('guest'),
        ];
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $this->signUp->request($request->all());

        return response()->json([
            'message' => 'User Created. Check your email and click on the link to confirm registration.',
        ]);
    }

    public function confirm(string $token): JsonResponse
    {
        $this->signUp->confirm($token);

        return response()->json([
            'message' => 'Confirmed',
        ]);
    }
}
