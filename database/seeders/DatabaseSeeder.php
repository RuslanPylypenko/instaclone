<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\HashTag;
use App\Models\Post;
use App\Models\User\UserEntity;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        UserEntity::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'nick' => 'agent007',
            'email' => 'test@example.com',
        ])->each(
            function (UserEntity $user) {
                $user->posts()->saveMany(Post::factory(random_int(4,20))->make());
            }
        );

        HashTag::factory(1)->create();

        $hashtags = HashTag::all();

        UserEntity::factory(1)->create()->each(function (UserEntity $user) use ($hashtags) {
            $user->posts()->saveMany(
                Post::factory(random_int(4,20))->create(['author_id' => $user->id])->each(function (Post $post) use ($hashtags) {
                    $post->comments()->saveMany(
                        Comment::factory(random_int(0, 40))->crekate(['author_id' => UserEntity::query()->inRandomOrder()->first(), 'post_id' => $post->id])
                    );
                    $post->hashTags()->saveMany(
                        $hashtags->shuffle()->take(random_int(0, 4))
                    );
                })
            );
        });

        $this->call(UserFollowsSeeder::class);
    }
}
