<?php

namespace App\UseCases\Post;

use App\Models\Comment as CommentEntity;
use App\Models\Post;
use App\Models\User\UserEntity;

class Comment
{
    public function add(UserEntity $author, Post $post, array $data): CommentEntity
    {
        return CommentEntity::create([
            'author_id' => $author->getKey(),
            'post_id' => $post->getKey(),
            'text' => $data['text'],
            'created_at' => now(),
            'update_at' => now(),
        ]);
    }

    public function edit(CommentEntity $comment, string $text): CommentEntity
    {
         $comment->update(['text' => $text]);

         return $comment;
    }
}
