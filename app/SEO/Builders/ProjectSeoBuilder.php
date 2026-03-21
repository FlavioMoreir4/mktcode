<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\Models\Project;
use App\SEO\DTO\SeoData;
use App\Settings\GeneralSettings;

class ProjectSeoBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    public function build(Project $project): SeoData
    {
        $url = route('public.projects.show', $project->slug);

        $coverUrl = $project->getFirstMediaUrl('cover');
        $image = ! blank($coverUrl) ? $coverUrl : $this->settings->ogImageUrl();

        // Keywords dinâmicas: título + tags do projeto + settings globais
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

            publishedAt: $project->published_at?->toIso8601String(),
            updatedAt: $project->updated_at?->toIso8601String(),

            author: $project->relationLoaded('author')
                ? $project->author->name
                : $this->settings->site_author,

            category: $project->relationLoaded('category') && $project->category
                ? ['name' => $project->category->name, 'slug' => $project->category->slug]
                : null,

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
