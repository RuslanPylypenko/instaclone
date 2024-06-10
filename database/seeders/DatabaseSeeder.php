<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\HashTag;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User\UserEntity;
use App\Services\Post\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

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

        HashTag::factory(100)->create();

        $hashtags = HashTag::all();

        UserEntity::factory(10)->create()->each(function (UserEntity $user) use ($hashtags) {
            $user->posts()->saveMany(
                Post::factory(random_int(4,20))->create(['author_id' => $user->id])->each(function (Post $post) use ($hashtags) {
                    $post->comments()->saveMany(
                        Comment::factory(random_int(0, 40))->create(['author_id' => UserEntity::query()->inRandomOrder()->first(), 'post_id' => $post->id])
                    );
                    $post->hashTags()->saveMany(
                        $hashtags->shuffle()->take(random_int(0, 4))
                    );

                    $this->assignImage($post);
                })
            );
        });

        $this->call(UserFollowsSeeder::class);
    }

    private function assignImage(Post $post)
    {
        $imagesDir = base_path() . '/database/factories/data/images';
        $images   = array_filter(scandir($imagesDir), fn($item) => is_file($imagesDir . '/' . $item));
        /** @var ImageService $imageService */
        $imageService = resolve(ImageService::class);

        foreach (fake()->randomElements($images, random_int(1, 3)) as $image){
            $uploadedFile = new UploadedFile($imagesDir . '/' . $image, basename($imagesDir . '/' . $image));
            $imageService->uploadImage($post, $uploadedFile);
        }
    }
}
