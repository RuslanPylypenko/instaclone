<?php

declare(strict_types=1);

namespace App\Repositories;

interface UsersRepository
{
    public function getUsers(): array;

    public function findUser(string $query): array;
}
