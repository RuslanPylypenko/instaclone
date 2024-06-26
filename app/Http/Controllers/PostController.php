<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\DetailResource;
use App\Http\Resources\Post\SummaryCollection;
use App\Models\Post;
use App\Repositories\PostsRepository;
use App\Services\Post\PostService;
use Exception;
use App\UseCases\Post\Likes;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct(
        private readonly PostsRepository $postsRepository,
        private readonly PostService     $postService,
        private readonly Likes $likes,
    ) {
    }

    public function userPosts(int $userId): Response
    {
        return response()->json(new SummaryCollection($this->postsRepository->findByUserId($userId)));
    }

    /**
     * @throws Exception
     */
    public function createPost(CreatePostRequest $request): Response
    {
        return response()->json([
            'data' => new DetailResource($this->postService->addPost($request->user(), $request->all())),
        ], Response::HTTP_CREATED);
    }

    public function updatePost(int $postId, UpdatePostRequest $request): Response
    {
        /** @var Post $post */
        $post = $this->postsRepository->getById($postId);

        return response()->json([
            'data' => new DetailResource($this->postService->updatePost($post, $request->all())),
        ], Response::HTTP_OK);
    }

    public function show(string $token): Response
    {
        return response()->json([
            'data' => new DetailResource($this->postsRepository->getByToken($token)),
        ]);
    }

    public function addLike(Request $request, Post $post): Response
    {
        $this->likes->likeOrUnlike($request->user(), $post);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function deletePost(int $postId): Response
    {
        /** @var Post $post */
        $post = $this->postsRepository->getById($postId);
        $this->postService->deletePost($post);

        return response()->noContent();
    }
}
