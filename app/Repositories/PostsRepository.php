<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\HashTag;
use App\Models\Post;
use App\Models\User\UserEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostsRepository
{
    public function findByUserId(int $userId): LengthAwarePaginator;

    public function getByToken(string $token): Post;

    public function findByHashTag(HashTag $hashtag): LengthAwarePaginator;

    public function getFeed(UserEntity $user): LengthAwarePaginator;

    public function findPost(string $query);
}
