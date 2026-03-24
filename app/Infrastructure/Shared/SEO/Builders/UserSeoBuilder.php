<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\SEO\Builders;

use App\Application\Shared\DTOs\SeoData;
use App\Domain\Shared\Contracts\SeoDataBuilder;
use App\Models\User;
use App\Settings\GeneralSettings;
use InvalidArgumentException;

class UserSeoBuilder implements SeoDataBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    public function supports(object $resource): bool
    {
        return $resource instanceof User;
    }

    public function build(object $user): SeoData
    {
        if (! $user instanceof User) {
            throw new InvalidArgumentException('UserSeoBuilder expects a User model.');
        }

        $url = route('public.user.show', $user->username);
        $image = ! blank($user->profile_photo_url)
            ? $user->profile_photo_url
            : $this->settings->ogImageUrl();

        $keywords = array_values(array_filter([
            $user->name,
            $user->username,
            $user->title,
        ]));

        return new SeoData(
            title: $user->name.($user->title ? ' - '.$user->title : ''),
            description: $user->seo_description
                ?? str($user->bio)->limit(155)->toString()
                ?? $this->settings->site_description,
            image: $image,
            imageAlt: $user->name,
            url: $url,
            canonical: $url,
            type: 'profile',
            author: $user->name,
            keywords: $keywords,
            locale: $this->settings->site_locale,
            breadcrumbs: [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => $user->name, 'url' => $url],
            ],
        );
    }
}
