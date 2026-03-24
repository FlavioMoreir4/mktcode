<?php

declare(strict_types=1);

namespace App\Application\Content\Commands;

use App\Application\Content\DTOs\PostPublicationData;
use App\Domain\Content\Enums\PostStatus;
use App\Models\Post;

class UpdatePostPublication
{
    public function __construct(
        private readonly PublishPost $publishPost,
        private readonly SchedulePost $schedulePost,
        private readonly UnpublishPost $unpublishPost,
    ) {}

    public function handle(Post $post, PostPublicationData $publication): Post
    {
        if ($publication->status === PostStatus::Draft) {
            return $this->unpublishPost->handle($post);
        }

        if ($publication->publishedAt?->isFuture()) {
            return $this->schedulePost->handle($post, $publication);
        }

        return $this->publishPost->handle($post, $publication);
    }
}
