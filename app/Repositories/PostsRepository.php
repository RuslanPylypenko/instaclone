<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\HashTag;
use App\Models\Post;
use App\Models\User\UserEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PostsRepository
{
    public function findByUserId(int $userId): LengthAwarePaginator
    {
        return Post::query()->where('author_id', $userId)->paginate();
    }

    public function getByToken(string $token): Model
    {
        return Post::query()->where('token', $token)->firstOrFail();
    }

    public function getById(int $id): Model
    {
        return Post::query()->findOrFail($id);
    }

    public function findByHashTag(HashTag $hashtag): LengthAwarePaginator
    {
        return Post::query()->paginate();
    }

    public function getFeed(UserEntity $user): LengthAwarePaginator
    {
        return Post::query()->paginate();
    }

    public function findPost(string $query): ?Post
    {
        return Post::query()->firstWhere('query', $query);
    }
}
