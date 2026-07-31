<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\SEO;

use App\Application\Shared\DTOs\SeoData;
use App\Infrastructure\Shared\SEO\Builders\PageSeoBuilder;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;

class SeoService
{
    public function __construct(
        private readonly PageSeoBuilder $pageBuilder,
        private readonly SeoRegistry $registry,
    ) {}

    /**
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

    public function forPost(Post $post): SeoData
    {
        return $this->registry->build($post);
    }

    public function forProject(Project $project): SeoData
    {
        return $this->registry->build($project);
    }

    public function forUser(User $user): SeoData
    {
        return $this->registry->build($user);
    }

    public function forPageModel(Page $page): SeoData
    {
        return $this->registry->build($page);
    }
}
