<?php

declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Shared\Sitemap\Providers\ContentPageSitemapProvider;
use App\Infrastructure\Shared\Sitemap\Providers\PageSitemapProvider;
use App\Infrastructure\Shared\Sitemap\Providers\PostSitemapProvider;
use App\Infrastructure\Shared\Sitemap\Providers\ProjectSitemapProvider;
use App\Infrastructure\Shared\Sitemap\Providers\UserSitemapProvider;
use App\Infrastructure\Shared\Sitemap\SitemapGenerator;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SitemapGenerator::class, function ($app): SitemapGenerator {
            return new SitemapGenerator([
                new PageSitemapProvider,
                $app->make(ContentPageSitemapProvider::class),
                $app->make(PostSitemapProvider::class),
                $app->make(ProjectSitemapProvider::class),
                $app->make(UserSitemapProvider::class),
            ]);
        });
    }
}
