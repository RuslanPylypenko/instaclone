<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UserService
{
    public function createUser(array $data): User;

    public function updateUser(User $user, array $data): User;

    public function follow(User $from, User $to): void;

    public function unFollow(User $from, User $to): void;
}

