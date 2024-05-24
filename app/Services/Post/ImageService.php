<?php

declare(strict_types=1);

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function uploadImage(Post $post, UploadedFile $image): void
    {
        $path = sprintf(
            '%s/%s.%s',
            Str::random(4),
            $image->getClientOriginalName(),
            $image->getClientOriginalExtension()
        );

        if (Storage::put($path, $post)) {
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
