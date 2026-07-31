<?php

declare(strict_types=1);

namespace App\Observers;

use App\Infrastructure\Shared\Sitemap\QueueSitemapGeneration;
use App\Models\Page;

/**
 * Regenerates public search metadata whenever a public page changes.
 */
class PageObserver
{
    public function __construct(private readonly QueueSitemapGeneration $queueSitemapGeneration) {}

    public function saved(Page $page): void
    {
        if ($page->status === \App\Domain\Content\Enums\PageStatus::Published) {
            $this->queueSitemapGeneration->dispatch();
        }
    }

    public function deleted(Page $page): void
    {
        $this->queueSitemapGeneration->dispatch();
    }
}
