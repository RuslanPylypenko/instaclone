<?php

namespace Database\Factories;

use App\Services\Post\TokenGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function __construct(private TokenGenerator $tokenGenerator)
    {
        parent::__construct();
    }

    public function definition(): array
    {
        return [
            'token' => $this->tokenGenerator->generate(),
            'text' => fake('uk-Ua')->text,
            'likes' => random_int(2, 100),
        ];
    }
}
