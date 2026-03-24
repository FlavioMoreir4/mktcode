<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\SEO\Builders;

use App\Application\Shared\DTOs\SeoData;
use App\Domain\Shared\Contracts\SeoDataBuilder;
use App\Models\Project;
use App\Settings\GeneralSettings;
use InvalidArgumentException;

class ProjectSeoBuilder implements SeoDataBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    public function supports(object $resource): bool
    {
        return $resource instanceof Project;
    }

    public function build(object $project): SeoData
    {
        if (! $project instanceof Project) {
            throw new InvalidArgumentException('ProjectSeoBuilder expects a Project model.');
        }

        $url = route('public.projects.show', $project->slug);
        $coverUrl = $project->getFirstMediaUrl('cover');
        $image = ! blank($coverUrl) ? $coverUrl : $this->settings->ogImageUrl();

        $tagKeywords = $project->relationLoaded('tags')
            ? $project->tags->pluck('name')->toArray()
            : [];

        $keywords = array_values(array_unique(
            array_merge([$project->title], $tagKeywords, $this->settings->parsedKeywords())
        ));

        return new SeoData(
            title: $project->seo_title ?? $project->title,
            description: $project->seo_description
                ?? str($project->description)->limit(155)->toString()
                ?? $this->settings->site_description,
            image: $image,
            imageAlt: $project->title,
            url: $url,
            canonical: $url,
            type: 'project',
            updatedAt: $project->updated_at?->toIso8601String(),
            author: $project->relationLoaded('author') && $project->author
                ? $project->author->name
                : $this->settings->site_author,
            keywords: $keywords,
            locale: $this->settings->site_locale,
            breadcrumbs: [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Projetos', 'url' => route('public.projects')],
                ['name' => $project->title, 'url' => $url],
            ],
        );
    }
}
