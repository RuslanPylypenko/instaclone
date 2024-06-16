<?php

namespace Tests\Feature;

use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('ftp');
    }

    public function test_post_store(): void
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE
        ]);

        $data = [
            'text' => fake()->text,
            'images' => [File::create('image.jpg', 1990)],
            'hashtags' => ['test'],
        ];
        $response = $this->actingAs($user, 'sanctum')->post(
            route('posts.store'),
            $data,
            ['Accept' => 'application/json']
        );

        $response->assertCreated();
        $this->assertDatabaseCount('posts', 1);
    }
}
