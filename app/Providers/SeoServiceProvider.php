<?php

declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Shared\SEO\Builders\ContentPageSeoBuilder;
use App\Infrastructure\Shared\SEO\Builders\PostSeoBuilder;
use App\Infrastructure\Shared\SEO\Builders\ProjectSeoBuilder;
use App\Infrastructure\Shared\SEO\Builders\UserSeoBuilder;
use App\Infrastructure\Shared\SEO\SeoRegistry;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SeoRegistry::class, function ($app): SeoRegistry {
            return new SeoRegistry([
                $app->make(ContentPageSeoBuilder::class),
                $app->make(PostSeoBuilder::class),
                $app->make(ProjectSeoBuilder::class),
                $app->make(UserSeoBuilder::class),
            ]);
        });
    }
}
