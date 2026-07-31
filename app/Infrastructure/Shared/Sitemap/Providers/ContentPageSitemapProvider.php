<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Sitemap\Providers;

use App\Application\Content\Queries\ListPublicPagesForSitemapQuery;
use App\Domain\Shared\Contracts\SitemapEntryProvider;
use App\Models\Page;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class ContentPageSitemapProvider implements SitemapEntryProvider
{
    public function __construct(private readonly ListPublicPagesForSitemapQuery $pages) {}

    public function generate(Sitemap $sitemap): void
    {
        $this->pages
            ->cursor()
            ->each(function (Page $page) use ($sitemap): void {
                $sitemap->add(
                    Url::create(route('public.page.show', $page->slug))
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6)
                );
            });
    }

    public function filename(): string
    {
        return 'sitemap-content-pages.xml';
    }
}
