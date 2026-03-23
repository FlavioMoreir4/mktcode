<?php

declare(strict_types=1);

namespace App\SEO;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use App\SEO\Builders\PostSeoBuilder;
use App\SEO\Builders\ProjectSeoBuilder;
use App\SEO\Builders\UserSeoBuilder;
use App\SEO\Contracts\HasSeo;
use App\SEO\DTO\SeoData;
use InvalidArgumentException;

class SeoResolver
{
    public function __construct(
        private readonly PostSeoBuilder $postBuilder,
        private readonly ProjectSeoBuilder $projectBuilder,
        private readonly UserSeoBuilder $userBuilder,
    ) {}

    public function resolve(HasSeo $model): SeoData
    {
        return match (true) {
            $model instanceof Post => $this->postBuilder->build($model),
            $model instanceof Project => $this->projectBuilder->build($model),
            $model instanceof User => $this->userBuilder->build($model),
            default => throw new InvalidArgumentException('Seo builder not configured for '.get_class($model)),
        };
    }
}
