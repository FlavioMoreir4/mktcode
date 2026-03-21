<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\SEO\Services\SeoService;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(SeoService $seo): Response
    {
        return Inertia::render('public/About', [
            'seo' => $seo->forPage(
                route: 'public.about',
                title: 'Sobre',
                description: 'Sobre a MKT Code',
            ),
        ]);
    }
}
