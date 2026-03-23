<?php

declare(strict_types=1);

namespace App\Observers;

use App\Console\Commands\GenerateSitemap;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

/**
 * Regenerates the public user sitemap when profile-facing attributes change.
 */
class UserObserver
{
    private array $sitemapFields = [
        'username', 'name', 'title', 'bio', 'location',
    ];

    public function saved(User $user): void
    {
        $changed = array_intersect(
            array_keys($user->getChanges()),
            $this->sitemapFields
        );

        if (! empty($changed)) {
            Artisan::queue(GenerateSitemap::SIGNATURE);
        }
    }

    public function deleted(User $user): void
    {
        Artisan::queue(GenerateSitemap::SIGNATURE);
    }
}
