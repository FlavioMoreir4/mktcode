<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\SEO\Builders\PageSeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function __invoke(): Response
    {
        $seo = (new PageSeoBuilder)->build(
            route: 'public.contact',
            title: 'Contato',
            description: 'Entre em contato com a MKT Code',
            keywords: ['Marketing & Code', 'desenvolvimento web']
        );

        return Inertia::render('public/Contact', [
            'seo' => $seo,
        ]);
    }
}
