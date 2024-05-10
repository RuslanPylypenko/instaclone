<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface UsersRepository
{
    public function findAll(?string $query): LengthAwarePaginator;
}
