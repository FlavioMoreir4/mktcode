<?php

declare(strict_types=1);

namespace App\SEO\Contracts;

use Spatie\Sitemap\Sitemap;

interface SitemapProvider
{
    public function generate(Sitemap $sitemap): void;

    public function filename(): string;
}
