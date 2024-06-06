<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\User\UserEntity;

interface UserService
{
    public function createUser(array $data): UserEntity;

    public function updateUser(UserEntity $user, array $data): UserEntity;

    public function follow(UserEntity $from, UserEntity $to): void;

    public function unFollow(UserEntity $from, UserEntity $to): void;
}

