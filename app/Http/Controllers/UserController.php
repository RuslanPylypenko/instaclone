<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\DetailResource;
use App\Http\Resources\User\SummaryCollection;
use App\Models\User;
use App\Repositories\UsersRepository;
use App\Services\UserService;
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

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => new DetailResource($user)
        ]);
    }

    public function follow(User $user, Request $request): JsonResponse
    {
        /** @var User $auth */
        $auth = $request->user();
        $this->userService->follow($auth, $user);
        return response('', 200)->json();
    }

    public function unfollow(User $user, Request $request): JsonResponse
    {
        /** @var User $auth */
        $auth = $request->user();
        $this->userService->unFollow($auth, $user);
        return response('', 200)->json();
    }
}
