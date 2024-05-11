<?php

declare(strict_types=1);

namespace App\Models\User;

enum UserStatus: string
{
    case NEW = 'new';
    case CONFIRMED = 'confirmed';
    case LONG_TIME_INACTIVE = 'inactive';
    case DELETED = 'deleted';

    public static function all(): array
    {
        return array_map(fn(UserStatus $status) => $status->value, self::cases());
    }
}
