<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class PasswordHasher
{
    public function hash(string $password): string
    {
       return Hash::make($password);
    }

    public function isValid(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
