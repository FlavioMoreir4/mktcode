<?php

declare(strict_types=1);

namespace App\SEO\Services;

class RobotsGenerator
{
    public function generate(): string
    {
        return implode("\n", [
            'User-agent: *',
            'Allow: /',
            '',
            'Sitemap: '.route('sitemap'),
        ]);
    }
}
