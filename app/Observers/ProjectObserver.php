<?php

declare(strict_types=1);

namespace App\Observers;

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Infrastructure\Shared\Sitemap\QueueSitemapGeneration;
use App\Models\Project;

/**
 * Regenerates public search metadata whenever a public project changes.
 */
class ProjectObserver
{
    public function __construct(private readonly QueueSitemapGeneration $queueSitemapGeneration) {}

    public function saved(Project $project): void
    {
        if ($project->status === ProjectStatus::Published) {
            $this->queueSitemapGeneration->dispatch();
        }
    }

    public function deleted(Project $project): void
    {
        $this->queueSitemapGeneration->dispatch();
    }
}
