<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post\SummaryCollection;
use App\Models\User\UserEntity;
use App\Repositories\PostsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __construct(
        private PostsRepository $postsRepository
    ) {
    }

    public function feed(Request $request): JsonResponse
    {
        /** @var UserEntity $user */
        $user = $request->getUser();

        return response()->json([
            'data' => new SummaryCollection($this->postsRepository->getFeed($user))
        ]);
    }
}
