<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Sitemap\Providers;

use App\Application\Portfolio\Queries\ListPublicProjectsForSitemapQuery;
use App\Domain\Shared\Contracts\SitemapEntryProvider;
use App\Models\Project;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class ProjectSitemapProvider implements SitemapEntryProvider
{
    public function __construct(private readonly ListPublicProjectsForSitemapQuery $projects) {}

    public function generate(Sitemap $sitemap): void
    {
        $this->projects
            ->cursor()
            ->each(function (Project $project) use ($sitemap): void {
                $url = Url::create(route('public.projects.show', $project->slug))
                    ->setLastModificationDate($project->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7);

                $cover = $project->getFirstMedia('cover');
                if ($cover) {
                    $url->addImage($cover->getUrl(), $project->seo_title ?? $project->title);
                }

                $sitemap->add($url);
            });
    }

    public function filename(): string
    {
        return 'sitemap-projects.xml';
    }
}
