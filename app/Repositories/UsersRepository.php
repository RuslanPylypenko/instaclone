<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersRepository
{
    public function findAll(?string $query): LengthAwarePaginator
    {
        //todo upgrade it
        $q = UserEntity::query();
        return $q->paginate(10);
    }

    public function findByEmail(string $email): ?UserEntity
    {
       return UserEntity::where('email', $email)->first();
    }

    /**
     * @return Collection<UserEntity>
     */
    public function findUncheckedInactive(): Collection
    {
        return UserEntity::query()
        ->whereDate('last_visit', '<=', now()->add('-1 year'))
        ->whereNot('status', '=', UserStatus::LONG_TIME_INACTIVE->value)
        ->get();
    }

    public function getByConfirmToken(string $token): UserEntity
    {
        return UserEntity::where('confirm_token', $token)->firstOrFail();
    }
}
