<?php

namespace Feature;

use App\Models\Post;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Services\Post\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('ftp');

        $this->postService = app(PostService::class);
    }

    #[Test] public function a_post_can_be_deleted_auth_user()
    {
        $this->withoutExceptionHandling();

        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $data = [
            'text' => fake()->text,
            'images' => [File::create('image.jpg', 1990)],
            'hashtags' => ['test'],
        ];

        $post =  $this->postService->addPost($user, $data);

        $res = $this->actingAs($user, 'sanctum')->delete(route('posts.destroy', ['id' => $post->id]));

        $res->assertNoContent();

        $this->assertDatabaseCount('posts', 0);
    }

    #[Test] public function a_post_can_be_deleted_by_only_auth_user()
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $data = [
            'text' => fake()->text,
            'images' => [File::create('image.jpg', 1990)],
            'hashtags' => ['test'],
        ];

        $post = $this->postService->addPost($user, $data);

        $res = $this->delete(route('posts.destroy', ['id' => $post->id]));

        $res->assertStatus(302);
        $res->assertRedirect('login');
    }

}
