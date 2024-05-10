<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\DetailResource as UserDetailResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'author'     => new UserDetailResource($this->whenLoaded('author')),
            'text'       => $this->text,
            'created_at' => $this->created_at,
            'updated_at' => $this->created_at,
        ];
    }
}
