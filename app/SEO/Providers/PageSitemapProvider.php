<?php

declare(strict_types=1);

namespace App\SEO\Providers;

use App\SEO\Contracts\SitemapProvider;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class PageSitemapProvider implements SitemapProvider
{
    public function generate(Sitemap $sitemap): void
    {
        $pages = [
            ['route' => 'home', 'priority' => 1.0],
            ['route' => 'public.services', 'priority' => 0.9],
            ['route' => 'public.projects', 'priority' => 0.9],
            ['route' => 'public.blog.index', 'priority' => 0.9],
            ['route' => 'public.about', 'priority' => 0.7],
            ['route' => 'public.contact', 'priority' => 0.5],
        ];

        foreach ($pages as $page) {
            $sitemap->add(
                Url::create(route($page['route']))
                    ->setPriority($page['priority'])
            );
        }
    }

    public function filename(): string
    {
        return 'sitemap-pages.xml';
    }
}
