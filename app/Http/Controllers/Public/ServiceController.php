<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Infrastructure\Shared\SEO\SeoService;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function __invoke(SeoService $seo): Response
    {
        return Inertia::render('public/Services', [
            'services' => Service::active()->get(),
            'seo' => $seo->forPage(
                route: 'public.services',
                title: 'Serviços',
            ),
        ]);
    }
}
