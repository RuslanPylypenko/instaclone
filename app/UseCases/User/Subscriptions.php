<?php

namespace App\UseCases\User;

use App\Models\User\UserEntity;

class Subscriptions
{
    public function follow(UserEntity $user, UserEntity $follower): void
    {
        $user->follow($follower);
        $user->save();
    }

    public function unfollow(UserEntity $user, UserEntity $follower): void
    {
        $user->unfollow($follower);
        $user->save();
    }
}
