<?php

namespace App\Services\Post;

use Illuminate\Support\Str;

class TokenGenerator
{
    private string $token;
    private \DateTime $expiresAt;

    public function __construct()
    {
        $this->token = Str::random(12);
        $this->expiresAt = now()->addDay();
    }

    public function generate(): string
    {
        return $this->token;
    }

    public static function create(): TokenGenerator
    {
        return new self();
    }

    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return now() > $this->expiresAt;
    }
}
