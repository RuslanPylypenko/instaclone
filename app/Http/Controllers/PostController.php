<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Post\DetailResource;
use App\Http\Resources\Post\SummaryCollection;
use App\Repositories\PostsRepository;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(
        private PostsRepository $postsRepository,
        private PostService $postService,
    ) {
    }

    public function userPosts(int $userId): JsonResponse
    {
        return response()->json([
            'data' => new SummaryCollection($this->postsRepository->findByUserId($userId))
        ]);
    }

    public function createPost(CreatePostRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'data' => new DetailResource($this->postService->addPost($request->all()))
            ]);
        } catch (\Throwable $throwable) {
            return response()->json([
                'error' => $throwable->getMessage()
            ]);
        }
    }
}
