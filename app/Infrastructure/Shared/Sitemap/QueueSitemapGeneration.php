<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Sitemap;

use App\Console\Commands\GenerateSitemap;
use Illuminate\Support\Facades\Artisan;

class QueueSitemapGeneration
{
    public function dispatch(): void
    {
        Artisan::queue(GenerateSitemap::SIGNATURE);
    }
}
