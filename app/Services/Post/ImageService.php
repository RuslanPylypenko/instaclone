<?php

declare(strict_types=1);

namespace App\Services\Post;

use App\Models\Post;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function uploadImage(Post $post, UploadedFile $image): void
    {
        $path = sprintf(
            '/images/%s/%s',
            Str::random(3),
            $image->getClientOriginalName(),
        );

        if (Storage::put($path, $image->getContent())) {
            $post->images()->create([
                'file_path' => $path,
            ]);
        } else {
            throw new \Exception('Failed to upload image');
        }
    }

    public function removeImage(string $path): void
    {

    }
}
