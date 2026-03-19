<?php

declare(strict_types=1);

namespace App\Http\Middleware;

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
        return [
            ...parent::share($request),
            'site' => [
                'name' => config('site.name', 'MC - Marketing & Code'),
                'url' => config('site.url', route('home')),
                'description' => config('site.description', 'Marketing & Code'),
                'og_image' => config('site.og_image', ''),
                'keywords' => config('site.keywords', ''),
                'author' => config('site.author', 'MC - Marketing & Code'),
            ],

            'seo' => [
                'title' => config('site.name', 'MC - Marketing & Code'),
                'description' => config('site.description', 'Marketing & Code'),
                'image' => config('site.og_image', ''),
                'url' => config('site.url', route('home')),
                'type' => 'website',
            ],

            // TODO: Revisar no futuro
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
