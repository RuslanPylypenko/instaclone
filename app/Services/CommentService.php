<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;

interface CommentService
{
    public function addComment(Post $post, int $authorId, string $text): Comment;

    public function updateComment(Comment $comment, string $text): Comment;

    public function deleteComment(Comment $comment): void;
}
