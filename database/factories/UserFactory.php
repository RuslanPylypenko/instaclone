<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'nick' => fake()->unique()->userName,
            'email' => fake()->unique()->safeEmail(),
            'avatar' => null,
            'bio' => fake()->realTextBetween(),
            'last_visit' => fake()->dateTimeBetween('-20 days'),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-10 years'),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    public function withPosts($count = 3)
    {
        return $this->afterCreating(function (User $user) use ($count) {
            $user->posts()->saveMany(Post::factory()->count($count)->make());
        });
    }
}
