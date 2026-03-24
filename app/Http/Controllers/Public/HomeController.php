<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Application\Portfolio\DTOs\PublicProjectViewData;
use App\Application\Portfolio\Queries\ListFeaturedHomeProjectsQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicProjectResource;
use App\Infrastructure\Shared\SEO\SeoService;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function __invoke(ListFeaturedHomeProjectsQuery $listFeaturedHomeProjects, SeoService $seo): Response
    {
        $projects = $listFeaturedHomeProjects
            ->take(3)
            ->map(fn ($project) => PublicProjectViewData::fromModel($project));

        return Inertia::render('Welcome', [
            'canRegister' => Features::enabled(Features::registration()),
            'projects' => PublicProjectResource::collection($projects)->resolve(),
            'services' => Service::active()->get(),
            'seo' => $seo->forPage(
                route: 'home',
                title: 'Tecnologia que resolve',
                description: 'Desenvolvimento de sites e sistemas com Laravel.',
            ),
        ]);
    }
}
