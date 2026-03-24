<?php

declare(strict_types=1);

namespace App\Application\Content\Commands;

use App\Application\Content\DTOs\PostPublicationData;
use App\Domain\Content\Enums\PostStatus;
use App\Models\Post;
use InvalidArgumentException;

class SchedulePost
{
    public function handle(Post $post, PostPublicationData $publication): Post
    {
        if ($publication->publishedAt === null || ! $publication->publishedAt->isFuture()) {
            throw new InvalidArgumentException('Scheduled posts require a future publication date.');
        }

        $post->status = PostStatus::Published;
        $post->published_at = $publication->publishedAt;
        $post->save();

        return $post->refresh();
    }
}
