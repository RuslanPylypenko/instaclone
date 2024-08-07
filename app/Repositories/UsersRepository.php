<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersRepository
{
    public function findAll(?string $query): LengthAwarePaginator
    {
        return UserEntity::query()->paginate();
    }

    public function findByEmail(string $email): ?object
    {
        return UserEntity::query()->where('email', $email)->first();
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

    /**
     * @return UserEntity|null
     */
    public function getByResetPasswordToken(string $token): ?Model
    {
        return UserEntity::query()->where('reset_password_token', $token)->firstOrFail();
    }

    /**
     * @return UserEntity|null>
     */
    public function findByToken(string $token): ?Model
    {
        return UserEntity::query()->where('token', $token)->firstOrFail();
    }
}
