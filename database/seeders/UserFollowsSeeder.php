<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User\UserEntity;
use Illuminate\Database\Seeder;

class UserFollowsSeeder extends Seeder
{
    public function run(): void
    {
        $users = UserEntity::all();

        foreach ($users as $user) {
            $followingCount = rand(1, 5);
            $followings = $users->random($followingCount);

            foreach ($followings as $following) {
                if ($user->id !== $following->id) {
                    $user->followings()->attach($following->id, ['created_at' => now(), 'updated_at' => now()]);
                }
            }
        }
    }
}
