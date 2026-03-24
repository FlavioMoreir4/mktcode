<?php

declare(strict_types=1);

namespace App\Domain\Shared\Contracts;

use Spatie\Sitemap\Sitemap;

interface SitemapEntryProvider
{
    public function generate(Sitemap $sitemap): void;

    public function filename(): string;
}
