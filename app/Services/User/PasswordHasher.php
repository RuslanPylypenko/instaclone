<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;

class PasswordHasher
{
    public function hash(string $password): string
    {
       return Hash::make($password);
    }
}
