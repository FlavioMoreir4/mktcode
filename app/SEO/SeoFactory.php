<?php

declare(strict_types=1);

namespace App\SEO;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use App\SEO\Builders\PostSeoBuilder;
use App\SEO\Builders\ProjectSeoBuilder;
use App\SEO\Builders\UserSeoBuilder;

class SeoFactory
{
    public static function forModel($model)
    {
        return match (true) {
            $model instanceof Post => (new PostSeoBuilder)->build($model),
            $model instanceof Project => (new ProjectSeoBuilder)->build($model),
            $model instanceof User => (new UserSeoBuilder)->build($model),
            default => null,
        };
    }
}
