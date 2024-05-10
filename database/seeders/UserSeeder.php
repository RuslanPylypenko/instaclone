<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'nick' => 'agent007',
            'email' => 'test@example.com',
        ])->each(
            function (User $user) {
                $user->posts()->saveMany(Post::factory(random_int(4,20))->make());
            }
        );

        User::factory(10)->create()->each(function (User $user) {
            $user->posts()->saveMany(Post::factory(random_int(4,20))->make());
        });
    }
}
