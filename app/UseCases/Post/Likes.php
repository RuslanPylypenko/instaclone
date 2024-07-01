<?php

namespace App\UseCases\Post;

use App\Models\Post;
use App\Models\User\UserEntity;

class Likes
{
    /**
     * @throws \Exception
     */
    public function likeOrUnlike(UserEntity $user, Post $post): void
    {
        $post->hasLike($user) ? $post->removeLike($user) : $post->addLike($user);
    }

    /**
     * @throws \Exception
     */
    public function like(UserEntity $user, Post $post): void
    {
        $post->addLike($user);

        $post->save();
    }

    /**
     * @throws \Exception
     */
    public function unlike(UserEntity $user, Post $post): void
    {
        $post->removeLike($user);
    }

    public function getUserLikes(UserEntity $entity): array
    {
        return $entity->likes()->pluck('post_id')->toArray();
    }
}
