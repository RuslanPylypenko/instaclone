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
            'token' => app(TokenGenerator::class)->generate(),
            'text' => fake('uk-Ua')->text,
            'likes' => random_int(2, 100),
        ];
    }
}
