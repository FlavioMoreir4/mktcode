<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Application\Portfolio\DTOs\PublicProjectViewData;
use App\Application\Portfolio\Queries\GetPublicProjectQuery;
use App\Application\Portfolio\Queries\ListPublicProjectsQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicProjectCollection;
use App\Http\Resources\Public\PublicProjectResource;
use App\Infrastructure\Shared\SEO\SeoService;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Public portfolio adapter backed by Inertia pages.
 */
class ProjectController extends Controller
{
    public function index(ListPublicProjectsQuery $listPublicProjects, SeoService $seo): Response
    {
        $projects = $listPublicProjects->paginate(9);
        $projects->setCollection(
            $projects->getCollection()->map(fn ($project) => PublicProjectViewData::fromModel($project))
        );

        return Inertia::render('public/project/Index', [
            'projects' => new PublicProjectCollection($projects),
            'seo' => $seo->forPage(
                route: 'public.projects',
                title: 'Projetos',
            ),
        ]);
    }

    public function show(string $project, GetPublicProjectQuery $getPublicProject, SeoService $seo): Response
    {
        $resolvedProject = $getPublicProject->findBySlug($project);
        if ($resolvedProject === null) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('public/project/Show', [
            'project' => PublicProjectResource::make(PublicProjectViewData::fromModel($resolvedProject))->resolve(),
            'seo' => $seo->forProject($resolvedProject),
        ]);
    }
}
