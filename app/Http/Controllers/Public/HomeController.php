<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicProjectResource;
use App\Models\Project;
use App\Models\Service;
use App\SEO\Services\SeoService;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function __invoke(SeoService $seo): Response
    {
        return Inertia::render('Welcome', [
            'canRegister' => Features::enabled(Features::registration()),
            'projects' => PublicProjectResource::collection(
                Project::published()->with(['author.media', 'media'])->latest()->take(3)->get()
            )->resolve(),
            'services' => Service::active()->get(),
            'seo' => $seo->forPage(
                route: 'home',
                title: 'Tecnologia que resolve',
                description: 'Desenvolvimento de sites e sistemas com Laravel.',
            ),
        ]);
    }
}
