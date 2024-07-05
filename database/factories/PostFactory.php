<?php

namespace Database\Factories;

use App\Services\Post\TokenGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'token' => random_int(0, 1000) . 'txt',
            'text' => fake('uk-Ua')->text,
        ];
    }
}
