<?php

namespace App\ValueObjects;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;

readonly class TokenValueObject
{
    public function __construct(
        public string $token,
        public DateTime $expiresAt
    ) {
    }

    public static function create(): TokenValueObject
    {
        $token = Str::random(32);
        $expiresAt = Carbon::now()->addDay();

        return new self($token, $expiresAt);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expiresAt);
    }
}
