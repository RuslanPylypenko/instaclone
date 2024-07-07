<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User\UserEntity;
use App\UseCases\Post\Likes;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function __construct(
        private readonly Likes          $likes,
        private readonly ImageService   $imageService,
        private readonly TokenGenerator $tokenGenerator
    ) {
    }

    /**
     * @throws Exception
     */
    public function addPost(UserEntity $user, array $data): Post
    {
        /** @var Post $post */
        $post = Post::create([
            'token' => $this->tokenGenerator->generate(),
            'text' => $data['text'],
            'author_id' => $user->getKey(),
        ]);

        if (! empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {
                $this->imageService->uploadImage($post, $image);
            }
        }

        if (! empty($data['hashtags']) && is_array($data['hashtags'])) {
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
        ]);

        if (! empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {
                $this->imageService->uploadImage($post, $image);
            }
        }

        if (! empty($data['hashtags']) && is_array($data['hashtags'])) {
            $post->hashTags()->delete();
            foreach ($data['hashtags'] as $hashtag) {
                $post->hashTags()->create(['name' => $hashtag]);
            }
        }

        return $post;
    }

    public function deletePost(Post $post): void
    {
        try {
            DB::transaction(function () use ($post) {
                if ($post->images()->exists()) {
                    $post->images()->delete();
                }

                if ($post->hashTags()->exists()) {
                    $post->hashTags()->delete();
                }

                if ($post->comments()->exists()) {
                    $post->comments()->delete();
                }

                if ($post->likes()->exists()) {
                    $post->likes()->delete();
                }

                $post->delete();
            });
        } catch (Exception $e) {
            Log::error('Failed to delete post: '.$e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function addLike(UserEntity $user, Post $post): void
    {
        $this->likes->likeOrUnlike($user, $post);
    }
}
