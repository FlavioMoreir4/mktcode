<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\SEO\Builders\PageSeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function __invoke(): Response
    {
        $seo = (new PageSeoBuilder)->build(
            route: 'public.services',
            title: 'Serviços',
            description: 'Serviços',
            keywords: ['Serviços']
        );

        return Inertia::render('public/Services', [
            'services' => Service::active()->get(),
            'seo' => $seo,
        ]);
    }
}
