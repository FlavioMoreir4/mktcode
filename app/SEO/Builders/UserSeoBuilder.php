<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\Models\User;
use App\SEO\DTO\SeoData;
use App\Settings\GeneralSettings;

class UserSeoBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    public function build(User $user): SeoData
    {
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
