<?php

declare(strict_types=1);

namespace App\Application\Content\Services;

use App\Application\Content\Commands\UpdatePostPublication;
use App\Application\Content\DTOs\PostPublicationData;
use App\Models\Post;

class PostPublicationWorkflow
{
    public function __construct(private readonly UpdatePostPublication $updatePostPublication) {}

    public function sync(Post $post, PostPublicationData $publication): void
    {
        $this->updatePostPublication->handle($post, $publication);
    }
}
