<?php

namespace App\Http\Resources\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
class DetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'text' => $this->text,
            'likes' => $this->likes()->count(),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'hashtags' => HashTagsResource::collection($this->whenLoaded('hashTags')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
