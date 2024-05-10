<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SummaryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return new DetailResource($item);
            }),
            'meta' => [
                'total'        => $this->total(),
                'per_page'     => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'from'         => $this->firstItem(),
                'to'           => $this->lastItem(),
            ],
        ];
    }
}
