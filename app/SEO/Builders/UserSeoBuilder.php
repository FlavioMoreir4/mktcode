<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\Models\User;
use App\SEO\DTO\SeoData;

class UserSeoBuilder
{
    public function build(User $user): SeoData
    {
        $url = route('public.user.show', $user->username);

        return new SeoData(
            /*
            |--------------------------------------------------------------------------
            | SEO básico
            |--------------------------------------------------------------------------
            */
            title: $user->name.($user->title ? ' - '.$user->title : ''),

            description: $user->seo_description
                ?? str($user->plain_text)->limit(155)
                ?? $user->name.' - Perfil profissional',

            /*
            |--------------------------------------------------------------------------
            | OpenGraph / Social
            |--------------------------------------------------------------------------
            */
            image: $user->profile_photo_url,
            imageAlt: $user->name,

            /*
            |--------------------------------------------------------------------------
            | URLs
            |--------------------------------------------------------------------------
            */
            url: $url,
            canonical: $url,

            /*
            |--------------------------------------------------------------------------
            | Tipo de página
            |--------------------------------------------------------------------------
            */
            type: 'profile',

            /*
            |--------------------------------------------------------------------------
            | Autor
            |--------------------------------------------------------------------------
            */
            author: $user->name,

            /*
            |--------------------------------------------------------------------------
            | Keywords automáticas para personal brand
            |--------------------------------------------------------------------------
            */
            keywords: array_filter([
                $user->name,
                $user->username,
                $user->title,
            ]),

            /*
            |--------------------------------------------------------------------------
            | Breadcrumbs SEO
            |--------------------------------------------------------------------------
            */
            breadcrumbs: [
                [
                    'name' => 'Home',
                    'url' => route('home'),
                ],
                // [
                //     'name' => 'Profissionais',
                //     'url' => route('public.user.index'),
                // ],
                [
                    'name' => $user->name,
                    'url' => $url,
                ],
            ],
        );
    }
}
