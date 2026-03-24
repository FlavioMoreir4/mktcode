<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Sitemap;

use App\Domain\Shared\Contracts\SitemapEntryProvider;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;

class SitemapGenerator
{
    /**
     * @param  iterable<int, SitemapEntryProvider>  $providers
     */
    public function __construct(private readonly iterable $providers) {}

    public function generate(): void
    {
        foreach ($this->providers as $provider) {
            $this->generateProvider($provider);
        }

        $this->generateIndex();
    }

    private function generateProvider(SitemapEntryProvider $provider): void
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
