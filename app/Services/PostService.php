<?php

namespace App\Services;

use App\Models\Post;

interface PostService
{
    public function addPost(array $data): Post;

    public function updatePost(Post $post, array $data): Post;

    public function deletePost(Post $post): void;

    public function addLike(Post $post): int;
}
