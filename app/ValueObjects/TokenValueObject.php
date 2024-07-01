<?php

namespace App\ValueObjects;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Hash;

class TokenValueObject
{
    public function __construct
    (
        public string   $token,
        public DateTime $expiresAt
    )
    {
    }

    public static function create(int $userId): TokenValueObject
    {
        $token = hash('sha256', $userId . time());
        $expiresAt = Carbon::now()->addDay();

        return new self($token, $expiresAt);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expiresAt);
    }

}
