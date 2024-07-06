<?php

namespace App\Services\Post;

use Illuminate\Support\Str;

class TokenGenerator
{
    public function generate(): string
    {
        return Str::random(12);
    }

    public function __construct(
        string $token,
        \DateTime $expiresAt
    ) {
    }

    public static function create(\DateTime $dateTime)
    {
        return new self(Str::random(12), $dateTime);
    }

    //    public function getExpiresAt(): \DateTime
    //    {
    //        return $this->expiresAt;
    //    }

    //    public function getToken(): string
    //    {
    //        return $this->token;
    //
    //    }

    //    public function isExpired(): bool
    //    {
    //        return $this->expiresAt < new \DateTime();
    //    }

}
