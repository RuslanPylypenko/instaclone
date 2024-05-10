<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostsRepository
{
    public function findByUserId(int $userId): LengthAwarePaginator;

    public function getByToken(string $token): Post;

    public function findByHashTag(Hashtag $hashtag): LengthAwarePaginator;

    public function getFeed(User $user): LengthAwarePaginator;

    public function findPost(string $query);
}
