<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Services\Post\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('ftp');

        $this->postService = app(PostService::class);
    }

    public function test_post_update(): void
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $data = [
            'text' => fake()->text,
            'images' => [File::create('image.jpg', 1990)],
            'hashtags' => ['test'],
        ];

       $post =  $this->postService->addPost($user, $data);

        $response = $this->actingAs($user, 'sanctum')->patch(
            route('posts.update', ['token' => $post->token]),
            ['text' => $text = 'Updated text'],
            ['Accept' => 'application/json']
        );

        $response->assertOk();

        $post->refresh();
        $this->assertEquals($text, $post->text);
    }

    public function test_post_update_forbidden(): void
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $user2 = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $data = [
            'text' => fake()->text,
            'images' => [File::create('image.jpg', 1990)],
            'hashtags' => ['test'],
        ];

        $post =  $this->postService->addPost($user, $data);

        $response = $this->actingAs($user2, 'sanctum')->patch(
            route('posts.update', ['token' => $post->token]),
            ['text' => 'Updated text'],
            ['Accept' => 'application/json']
        );

        $response->assertForbidden();
    }
}
