<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token'      => $this->token,
            'text'       => $this->text,
            'likes'      => $this->likes,
            'images'     => ImageResource::collection($this->whenLoaded('images')),
            'comments'   => CommentResource::collection($this->whenLoaded('comments')),
            'hashtags'   => HashTagsResource::collection($this->whenLoaded('hashTags')),
            'created_at' => $this->created_at
        ];
    }
}
