<?php

declare(strict_types=1);

namespace App\SEO\Services;

use App\SEO\Contracts\SitemapProvider;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;

class SitemapGenerator
{
    public function __construct(private array $providers) {}

    public function generate(): void
    {
        foreach ($this->providers as $provider) {
            $this->generateProvider($provider);
        }

        $this->generateIndex();
    }

    private function generateProvider(SitemapProvider $provider): void
    {
        $sitemap = Sitemap::create();

        $provider->generate($sitemap);

        $sitemap->writeToFile(public_path($provider->filename()));
    }

    private function generateIndex(): void
    {
        $index = SitemapIndex::create();

        foreach ($this->providers as $provider) {
            $index->add(url($provider->filename()));
        }

        $index->writeToFile(public_path('sitemap.xml'));
    }
}
