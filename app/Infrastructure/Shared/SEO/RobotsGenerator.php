<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\SEO;

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
