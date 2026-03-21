<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var GeneralSettings $settings */
        $settings = app(GeneralSettings::class);

        return [
            ...parent::share($request),

            /**
             * Dados globais do site — fonte de verdade: GeneralSettings (banco de dados).
             * O frontend consome via `usePage().props.site`.
             */
            'site' => [
                'name' => $settings->site_name,
                'url' => route('home'),
                'description' => $settings->site_description,
                'og_image' => $settings->ogImageUrl(),
                'keywords' => $settings->parsedKeywords(),
                'author' => $settings->site_author,
                'locale' => $settings->site_locale,
                'social_links' => $settings->activeSocialLinks(),
            ],

            'name' => $settings->site_name,
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
