<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\Models\Project;
use App\SEO\DTO\SeoData;

class ProjectSeoBuilder
{
    public function build(Project $project): SeoData
    {
        $url = route('public.projects.show', $project->slug);

        return new SeoData(
            /*
            |--------------------------------------------------------------------------
            | SEO básico
            |--------------------------------------------------------------------------
            */
            title: $project->seo_title ?? $project->title,
            description: $project->seo_description
                ?? str($project->description)->limit(155),

            /*
            |--------------------------------------------------------------------------
            | OpenGraph / Social
            |--------------------------------------------------------------------------
            */
            image: $project->getFirstMediaUrl('cover'),
            imageAlt: $project->title,

            /*
            |--------------------------------------------------------------------------
            | URLs
            |--------------------------------------------------------------------------
            */
            url: $url,
            canonical: $url,

            /*
            |--------------------------------------------------------------------------
            | Tipo de página
            |--------------------------------------------------------------------------
            */
            type: 'website',

            /*
            |--------------------------------------------------------------------------
            | Datas
            |--------------------------------------------------------------------------
            */
            publishedAt: $project->published_at?->toIso8601String(),
            updatedAt: $project->updated_at?->toIso8601String(),

            /*
            |--------------------------------------------------------------------------
            | Autor (se existir relacionamento)
            |--------------------------------------------------------------------------
            */
            author: $project->relationLoaded('author')
                ? $project->author->name
                : null,

            /*
            |--------------------------------------------------------------------------
            | Categoria (caso exista)
            |--------------------------------------------------------------------------
            */
            category: $project->relationLoaded('category') && $project->category
                ? [
                    'name' => $project->category->name,
                    'slug' => $project->category->slug,
                ]
                : null,

            /*
            |--------------------------------------------------------------------------
            | Keywords
            |--------------------------------------------------------------------------
            */
            keywords: [
                $project->title,
                'Projeto',
                'Portfólio',
                'Case Study',
            ],

            /*
            |--------------------------------------------------------------------------
            | Breadcrumbs SEO
            |--------------------------------------------------------------------------
            */
            breadcrumbs: [
                [
                    'name' => 'Home',
                    'url' => route('home'),
                ],
                [
                    'name' => 'Projetos',
                    'url' => route('public.projects'),
                ],
                [
                    'name' => $project->title,
                    'url' => $url,
                ],
            ],
        );
    }
}
