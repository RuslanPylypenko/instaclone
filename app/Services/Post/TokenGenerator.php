<?php

namespace App\Services\Post;

use Illuminate\Support\Str;

class TokenGenerator
{
    public function generate(): string
    {
         return Str::random(12);
    }
}
