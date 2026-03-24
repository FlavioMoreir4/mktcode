<?php

declare(strict_types=1);

namespace App\Observers;

use App\Domain\Content\Enums\PostStatus;
use App\Infrastructure\Shared\Sitemap\QueueSitemapGeneration;
use App\Models\Post;

/**
 * Regenerates public search metadata whenever a public post changes.
 */
class PostObserver
{
    public function __construct(private readonly QueueSitemapGeneration $queueSitemapGeneration) {}

    public function saved(Post $post): void
    {
        $isPublished = $post->status === PostStatus::Published
            && $post->published_at !== null
            && $post->published_at->isPast();

        $wasPublished = $post->getOriginal('status') === PostStatus::Published;

        if ($isPublished || $wasPublished) {
            $this->queueSitemapGeneration->dispatch();
        }
    }

    public function deleted(Post $post): void
    {
        $this->queueSitemapGeneration->dispatch();
    }
}
