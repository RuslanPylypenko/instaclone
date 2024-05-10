<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;

interface PostsRepository
{
    public function findByUserId(int $userId): array;

    public function getByToken(string $token): Post;

    public function findByHashTag(Hashtag $hashtag): array;

    public function getFeed(User $user): array;

    public function findPost(string $query);
}
