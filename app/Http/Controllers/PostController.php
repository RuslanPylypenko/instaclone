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
        return response()->json([
            'data' => new SummaryCollection($this->postsRepository->findByUserId($userId))
        ]);
    }

    public function createPost(CreatePostRequest $request): Response
    {
        //TODO guard
        //$this->can('create', Post::class);
        $user = auth()->user();
        if ($user->isWait()) {
            return response()->json([
                'message' => 'You need to confirm your account. Please check your email.'
            ], Response::HTTP_FORBIDDEN);
        }
        return response()->json([
            'data' => new DetailResource($this->postService->addPost($user, $request->all()))
        ], Response::HTTP_CREATED);
    }

    public function show(string $token): Response
    {
        return response()->json([
            'data' => new DetailResource($this->postsRepository->getByToken($token))
        ]);
    }
}
