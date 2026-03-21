<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\SEO\DTO\SeoData;
use App\Settings\GeneralSettings;

class PageSeoBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    /**
     * @param  string[]  $keywords
     * @param  array<array{name: string, url: string}>  $breadcrumbs
     */
    public function build(
        string $route,
        ?string $title = null,
        ?string $description = null,
        array $keywords = [],
        array $breadcrumbs = [],
        string $robots = 'index, follow',
    ): SeoData {
        $url = route($route);

        $resolvedKeywords = ! empty($keywords)
            ? $keywords
            : $this->settings->parsedKeywords();

        $resolvedTitle = $title ?? $this->settings->site_name;

        return new SeoData(
            title: $resolvedTitle,
            description: $description ?? $this->settings->site_description,
            image: $this->settings->ogImageUrl(),
            imageAlt: $resolvedTitle,
            url: $url,
            canonical: $url,
            type: 'website',
            keywords: $resolvedKeywords,
            robots: $robots,
            locale: $this->settings->site_locale,
            breadcrumbs: $breadcrumbs ?: [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => $resolvedTitle, 'url' => $url],
            ],
        );
    }
}
