<?php

namespace Feature;

use App\Models\Post;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_like_to_post_by_active_user()
    {
        $this->withoutExceptionHandling();

        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $user2 = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $post = Post::factory()->create([
            'author_id' => $user->id,
        ]);

        $response = $this->actingAs($user2, 'sanctum')->postJson(
            route('posts.like', ['post' => $post->id]),
        );

        $response->assertCreated();

        $this->assertDatabaseHas('post_likes', [
            'user_id' => $user2->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_add_like_to_post_by_unauthorized_user()
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $post = Post::factory()->create([
            'author_id' => $user->id,
        ]);

        $response = $this->postJson(
            route('posts.like', ['post' => $post->id]),
        );

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseCount('post_likes', 0);

        $response->assertUnauthorized();
    }

    public function test_remove_like_from_post_by_active_user()
    {
        $this->withoutExceptionHandling();

        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $user2 = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $post = Post::factory()->create([
            'author_id' => $user->id,
        ]);

        $this->actingAs($user2, 'sanctum')->postJson(
            route('posts.like', ['post' => $post->id])
        );

        $response = $this->actingAs($user2, 'sanctum')->postJson(
            route('posts.like', ['post' => $post->id])
        );

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseCount('post_likes', 0);

        $response->assertCreated();
    }
}
