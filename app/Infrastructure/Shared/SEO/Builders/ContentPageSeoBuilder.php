<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\SEO\Builders;

use App\Application\Shared\DTOs\SeoData;
use App\Domain\Shared\Contracts\SeoDataBuilder;
use App\Models\Page;
use App\Settings\GeneralSettings;
use InvalidArgumentException;

class ContentPageSeoBuilder implements SeoDataBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    public function supports(object $resource): bool
    {
        return $resource instanceof Page;
    }

    public function build(object $page): SeoData
    {
        if (! $page instanceof Page) {
            throw new InvalidArgumentException('ContentPageSeoBuilder expects a Page model.');
        }

        $url = route('public.page.show', $page->slug);

        return new SeoData(
            title: $page->seo_title ?? $page->title,
            description: $page->seo_description
                ?? $page->excerpt
                ?? str($page->plain_text)->limit(155)->toString()
                ?? $this->settings->site_description,
            image: $this->settings->ogImageUrl(),
            imageAlt: $page->title,
            url: $url,
            canonical: $url,
            type: 'article',
            publishedAt: $page->published_at?->toIso8601String(),
            updatedAt: $page->updated_at?->toIso8601String(),
            author: $this->settings->site_author,
            keywords: $this->settings->parsedKeywords(),
            locale: $this->settings->site_locale,
            breadcrumbs: [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => $page->title, 'url' => $url],
            ],
        );
    }
}
