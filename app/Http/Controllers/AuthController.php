<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use Illuminate\Http\Request;
use App\Http\Resources\User\DetailResource;
use App\Models\User\UserEntity;
use App\Repositories\UsersRepository;
use App\UseCases\User\SignUp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private SignUp          $signUpService,
        private UsersRepository $usersRepository,
    ) {
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $this->signUpService->request($request->all());

        return response()->json([
            'message' => 'User Created',
        ]);
    }

    public function confirm(Request $request): JsonResponse
    {
        $this->signUpService->confirm($request['token']);

        return response()->json([
            'message' => 'Confirmed',
        ]);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = $this->usersRepository->findByEmail($request['email']);
        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $token = $user->createToken($user->nick.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json(['user' => new DetailResource(auth()->user())]);
    }

    public function logout(): JsonResponse
    {
        /** @var UserEntity $user */
        $user = auth()->user();
        $user->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }
}
