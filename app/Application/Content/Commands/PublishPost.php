<?php

declare(strict_types=1);

namespace App\Application\Content\Commands;

use App\Application\Content\DTOs\PostPublicationData;
use App\Domain\Content\Enums\PostStatus;
use App\Models\Post;

class PublishPost
{
    public function handle(Post $post, PostPublicationData $publication): Post
    {
        $post->status = PostStatus::Published;
        $post->published_at = $publication->publishedAt ?? now();
        $post->save();

        return $post->refresh();
    }
}
