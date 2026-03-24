<?php

declare(strict_types=1);

namespace App\Observers;

use App\Infrastructure\Shared\Sitemap\QueueSitemapGeneration;
use App\Models\User;

/**
 * Regenerates the public user sitemap when profile-facing attributes change.
 */
class UserObserver
{
    private array $sitemapFields = [
        'username', 'name', 'title', 'bio', 'location',
    ];

    public function __construct(private readonly QueueSitemapGeneration $queueSitemapGeneration) {}

    public function saved(User $user): void
    {
        $changed = array_intersect(
            array_keys($user->getChanges()),
            $this->sitemapFields
        );

        if (! empty($changed)) {
            $this->queueSitemapGeneration->dispatch();
        }
    }

    public function deleted(User $user): void
    {
        $this->queueSitemapGeneration->dispatch();
    }
}
