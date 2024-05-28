<?php

declare(strict_types=1);

namespace App\UseCases;

use Illuminate\Http\UploadedFile;

interface ImageService
{
    public function uploadImage(UploadedFile $image): string;

    public function removeImage(string $path): void;
}
