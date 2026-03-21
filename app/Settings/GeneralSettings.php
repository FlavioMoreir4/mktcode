<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public string $site_description;

    public ?string $site_keywords;

    public ?string $og_image;

    public ?array $social_links;

    public string $contact_email;

    public string $site_author;

    public string $site_locale;

    public static function group(): string
    {
        return 'general';
    }

    /**
     * Retorna keywords como array seguro, parseado do campo CSV.
     *
     * @return string[]
     */
    public function parsedKeywords(): array
    {
        if (blank($this->site_keywords)) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode(',', $this->site_keywords))
        ));
    }

    /**
     * Retorna a URL pública da imagem OpenGraph (ou fallback para o logo).
     */
    public function ogImageUrl(): string
    {
        if (! blank($this->og_image)) {
            return asset('storage/'.$this->og_image);
        }

        return asset('images/logo.png');
    }

    /**
     * Retorna os social links como array seguro.
     *
     * @return array<string, string>
     */
    public function activeSocialLinks(): array
    {
        if (empty($this->social_links)) {
            return [];
        }

        return array_filter(
            $this->social_links,
            fn (string $url): bool => ! blank($url)
        );
    }
}
