<?php

declare(strict_types=1);

namespace App\SEO\Providers;

use App\Models\User;
use App\SEO\Contracts\SitemapProvider;
use Spatie\Sitemap\Sitemap;

class UserSitemapProvider implements SitemapProvider
{
    public function generate(Sitemap $sitemap): void
    {
        User::public()
            ->with('media')
            ->cursor()
            ->each(fn (User $user) => $sitemap->add($user));
    }

    public function filename(): string
    {
        return 'sitemap-users.xml';
    }
}
