<?php

declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Str;

class ConfirmTokenGenerator
{
    public function generate(): string
    {
        return Str::random(32);
    }
}
