<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\SEO\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    public const SIGNATURE = 'seo:generate-sitemap';

    protected $signature = self::SIGNATURE;

    protected $description = 'Generate sitemap files';

    public function handle(SitemapGenerator $generator): int
    {
        $this->info('Generating sitemap...');
        $generator->generate();
        $this->info('Sitemap generated.');

        return self::SUCCESS;
    }
}
