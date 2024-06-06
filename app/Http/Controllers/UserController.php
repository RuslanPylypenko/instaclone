<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\DetailResource;
use App\Http\Resources\User\SummaryCollection;
use App\Models\User\UserEntity;
use App\Repositories\UsersRepository;
use App\UseCases\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UsersRepository $usersRepository,
        private UserService $userService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new SummaryCollection($this->usersRepository->findAll($request->get('query')))
        ]);
    }

    public function show(UserEntity $user): JsonResponse
    {
        return response()->json([
            'data' => new DetailResource($user)
        ]);
    }

    public function follow(UserEntity $user, Request $request): JsonResponse
    {
        /** @var UserEntity $auth */
        $auth = $request->user();
        $this->userService->follow($auth, $user);
        return response('', 200)->json();
    }

    public function unfollow(UserEntity $user, Request $request): JsonResponse
    {
        /** @var UserEntity $auth */
        $auth = $request->user();
        $this->userService->unFollow($auth, $user);
        return response('', 200)->json();
    }
}
