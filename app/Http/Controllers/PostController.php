<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Post\DetailResource;
use App\Http\Resources\Post\SummaryCollection;
use App\Repositories\PostsRepository;
use App\Services\Post\PostService;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct(
        private PostsRepository $postsRepository,
        private PostService $postService,
    ) {
    }

    public function userPosts(int $userId): Response
    {
        return response()->json(new SummaryCollection($this->postsRepository->findByUserId($userId)));
    }

    public function createPost(CreatePostRequest $request): Response
    {
        return response()->json([
            'data' => new DetailResource($this->postService->addPost($request->user(), $request->all())),
        ], Response::HTTP_CREATED);
    }

    public function show(string $token): Response
    {
        return response()->json([
            'data' => new DetailResource($this->postsRepository->getByToken($token)),
        ]);
    }
}
