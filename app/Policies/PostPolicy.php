<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User\UserEntity;

class PostPolicy
{
    public function update(UserEntity $userEntity, Post $post): bool
    {
        return $userEntity->id === $post->author_id;
    }

    public function delete(UserEntity $userEntity, Post $post): bool
    {
        return $userEntity->id === $post->author_id;
    }
}
