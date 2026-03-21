<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\SEO\Services\SeoService;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function __invoke(SeoService $seo): Response
    {
        return Inertia::render('public/Contact', [
            'seo' => $seo->forPage(
                route: 'public.contact',
                title: 'Contato',
            ),
        ]);
    }
}
