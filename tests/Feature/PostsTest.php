<?php

namespace Tests\Feature;

use App\Models\Post;
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

    public function test_post_show(): void
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $post =  Post::factory()->create([
            'author_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->get(route('posts.show', ['token' => $post->token]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'token'      => $post->token,
                'text'       => $post->text,
                'likes'      => $post->likes->count(),
                'created_at' => $post->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function test_user_posts()
    {
        $user = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        $user2 = UserEntity::factory()->create([
            'status' => UserStatus::ACTIVE,
        ]);

        Post::factory(12)->create([
            'author_id' => $user2->id,
        ])->each(function (Post $post) use ($user) {
            $post->likes()->create(['user_id' => $user->id]);
        });

        Post::factory(3)->create([
            'author_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->get(route('users.show.posts', ['userId' => $user->id]));

        $response->assertOk();

        $posts = Post::query()->where('author_id', $user->id)->get();

        $response->assertJson([
            'data' => $posts->map(function (Post $post) {
                    return [
                        'token'      => $post->token,
                        'text'       => $post->text,
                        'likes'      => $post->likes()->count(),
                        'created_at' => $post->created_at->format('Y-m-d H:i:s'),
                    ];
                })->toArray(),
            'meta' => [
                'total'        => $posts->count(),
                'per_page'     => 15,
                'current_page' => 1,
                'last_page'    => 1,
                'from'         => 1,
                'to'           => $posts->count(),
            ],
        ]);
    }
}
