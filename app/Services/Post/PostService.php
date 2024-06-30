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

        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {
                $this->imageService->uploadImage($post, $image);
            }
        }

        if (!empty($data['hashtags']) && is_array($data['hashtags'])) {
            foreach ($data['hashtags'] as $hashtag) {
                $post->hashTags()->create(['name' => $hashtag]); // todo check if exists
            }
        }

        return $post;
    }

    public function updatePost(Post $post, array $data): Post
    {
        $post->update([
            'text' => $data['text'],
            'likes' => $data['likes'],
        ]);

        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {
                $this->imageService->uploadImage($post, $image);
            }
        }

        if (!empty($data['hashtags']) && is_array($data['hashtags'])) {
            $post->hashTags()->delete();
            foreach ($data['hashtags'] as $hashtag) {
                $post->hashTags()->create(['name' => $hashtag]);
            }
        }

        return $post;
    }

    public function deletePost(Post $post): void
    {

    }

    public function addLike(Post $post): int
    {

    }
}
