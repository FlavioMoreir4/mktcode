<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\SEO\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:generate-sitemap';

    protected $description = 'Generate sitemap files';

    public function handle(SitemapGenerator $generator)
    {
        $this->info('Generating sitemap...');
        $generator->generate();
        $this->info('Sitemap generated.');
    }
}
