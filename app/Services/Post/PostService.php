<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User\UserEntity;

class PostService
{
    public function __construct(
        private TokenGenerator $tokenGenerator,
        private ImageService   $imageService,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function addPost(UserEntity $user, array $data): Post
    {
        /** @var Post $post */
        $post = Post::create([
            'token' => $this->tokenGenerator->generate(),
            'text' => $data['text'],
            'author_id' => $user->getKey(),
            'likes' => 0,
        ]);

        foreach ($data['images'] as $image) {
           $this->imageService->uploadImage($post, $image);
        }

        foreach ($data['hashtags'] as $hashtag) {
            $post->hashTags()->create(['name' => $hashtag]); // todo check if exists
        }

        return $post;
    }

    public function updatePost(Post $post, array $data): Post
    {

    }

    public function deletePost(Post $post): void
    {

    }

    public function addLike(Post $post): int
    {

    }
}
