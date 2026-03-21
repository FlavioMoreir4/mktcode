<?php

declare(strict_types=1);

namespace App\SEO\Services;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use App\SEO\Builders\PageSeoBuilder;
use App\SEO\Builders\PostSeoBuilder;
use App\SEO\Builders\ProjectSeoBuilder;
use App\SEO\Builders\UserSeoBuilder;
use App\SEO\DTO\SeoData;

/**
 * Ponto central para resolução de SEO via injeção de dependência.
 * Elimina o uso de `app()` e `new` espalhados nos controllers.
 */
class SeoService
{
    public function __construct(
        protected PageSeoBuilder $pageBuilder,
        protected PostSeoBuilder $postBuilder,
        protected ProjectSeoBuilder $projectBuilder,
        protected UserSeoBuilder $userBuilder,
    ) {}

    /**
     * Gera SEO para uma página estática (home, about, etc.).
     *
     * @param  string[]  $keywords
     * @param  array<array{name: string, url: string}>  $breadcrumbs
     */
    public function forPage(
        string $route,
        ?string $title = null,
        ?string $description = null,
        array $keywords = [],
        array $breadcrumbs = [],
        string $robots = 'index, follow',
    ): SeoData {
        return $this->pageBuilder->build(
            route: $route,
            title: $title,
            description: $description,
            keywords: $keywords,
            breadcrumbs: $breadcrumbs,
            robots: $robots,
        );
    }

    /**
     * Gera SEO para uma página de post/artigo.
     */
    public function forPost(Post $post): SeoData
    {
        return $this->postBuilder->build($post);
    }

    /**
     * Gera SEO para uma página de projeto.
     */
    public function forProject(Project $project): SeoData
    {
        return $this->projectBuilder->build($project);
    }

    /**
     * Gera SEO para uma página de perfil de usuário.
     */
    public function forUser(User $user): SeoData
    {
        return $this->userBuilder->build($user);
    }
}
