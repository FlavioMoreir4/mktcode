<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

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
            Artisan::queue('app:generate-sitemap');
        }
    }

    public function deleted(User $user): void
    {
        Artisan::queue('app:generate-sitemap');
    }
}
