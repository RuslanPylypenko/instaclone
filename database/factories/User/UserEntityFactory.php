<?php

namespace Database\Factories\User;

use App\Models\User\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserEntity>
 */
class UserEntityFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'nick' => fake()->unique()->userName,
            'status' => fake()->randomElement([UserStatus::WAIT, UserStatus::ACTIVE]),
            'email' => fake()->unique()->safeEmail(),
            'avatar' => null,
            'bio' => fake()->realTextBetween(),
            'last_visit' => fake()->dateTimeBetween('-18 month'),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-10 years'),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
