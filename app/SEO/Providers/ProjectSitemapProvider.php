<?php

declare(strict_types=1);

namespace App\SEO\Providers;

use App\Models\Project;
use App\SEO\Contracts\SitemapProvider;
use Spatie\Sitemap\Sitemap;

class ProjectSitemapProvider implements SitemapProvider
{
    public function generate(Sitemap $sitemap): void
    {
        Project::public()
            ->with(['author', 'media'])
            ->cursor()
            ->each(fn (Project $project) => $sitemap->add($project));
    }

    public function filename(): string
    {
        return 'sitemap-projects.xml';
    }
}
