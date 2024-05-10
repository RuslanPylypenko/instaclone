<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;

interface CommentsRepository
{
    public function findByPost(Post $post): array;
}
