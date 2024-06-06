<?php

namespace App\Services\Post;

use App\Models\Post;

class PostService
{
    public function __construct(
        private TokenGenerator $tokenGenerator,
        private ImageService   $imageService,
    )
    {
    }

    public function addPost(array $data): Post
    {
        $post = Post::create([
            'token' => $this->tokenGenerator->generate(),
            'text' => $data['text'],
            'author_id' => $data['author_id'],
            'likes' => 0,
        ]);

        foreach ($data['images'] as $image) {
           $this->imageService->uploadImage($post, $image);
        }

        foreach ($data['hashtags'] as $hashtag) {
            $post->hashTags()->attach($hashtag);
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
