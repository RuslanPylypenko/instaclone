<?php

namespace Tests\Unit\Services\Post;

use App\Services\Post\ImageService;
use App\Services\Post\PostService;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Testing\File;
use PHPUnit\Framework\TestCase;

class ImageServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->postService = app(PostService::class);
        $this->imageService = app(ImageService::class);

    }
}
