<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\UserEntity;
use App\Repositories\UsersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller implements HasMiddleware
{
    public function __construct(
        private UsersRepository $usersRepository,
    ) {
    }

    public static function middleware()
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }

    public function login(LoginRequest $request): JsonResponse
    {
        /** @var UserEntity $user */
        $user = $this->usersRepository->findByEmail($request['email']);

        if (! $user || ! Hash::check($request['password'], $user->password)) {
            if ($user->isWait()) {
                Auth::logout();

                return response()->json([
                    'message' => 'You need to confirm your account. Please check your email.',
                ], 401);
            }

            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $token = $user->createToken($user->nick.'-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var UserEntity $user */
        $user = auth()->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'logged out',
        ]);
    }
}
