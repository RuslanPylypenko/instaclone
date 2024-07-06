<?php

declare(strict_types=1);

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function uploadImage(Post $post, UploadedFile $image): void
    {
        // TODO check if image is unique
        try {
            $path = sprintf(
                '/images/%s/%s',
                Str::random(3),
                $image->getClientOriginalName(),
            );

            if (Storage::put($path, $image->getContent())) {
                $post->images()->create([
                    'file_path' => $path,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage().$e->getFile().$e->getLine());
        }

    }

    public function removeImage(string $path): void
    {

    }
}
