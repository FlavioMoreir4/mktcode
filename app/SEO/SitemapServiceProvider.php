<?php

declare(strict_types=1);

namespace App\SEO;

use App\SEO\Providers\PageSitemapProvider;
use App\SEO\Providers\PostSitemapProvider;
use App\SEO\Providers\ProjectSitemapProvider;
use App\SEO\Providers\UserSitemapProvider;
use App\SEO\Services\SitemapGenerator;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SitemapGenerator::class, function () {
            return new SitemapGenerator([
                new PageSitemapProvider,
                new PostSitemapProvider,
                new ProjectSitemapProvider,
                new UserSitemapProvider,
            ]);
        });
    }
}
