<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicProjectCollection;
use App\Http\Resources\Public\PublicProjectResource;
use App\Models\Project;
use App\SEO\Services\SeoService;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Public portfolio adapter backed by Inertia pages.
 */
class ProjectController extends Controller
{
    public function index(SeoService $seo): Response
    {
        $projects = Project::publicOrdered()
            ->with(['author.media', 'media', 'tags'])
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('public/project/Index', [
            'projects' => new PublicProjectCollection($projects),
            'seo' => $seo->forPage(
                route: 'public.projects',
                title: 'Projetos',
            ),
        ]);
    }

    public function show(Project $project, SeoService $seo): Response
    {
        abort_unless($project->isPubliclyVisible(), 404);

        $project->load(['author.media', 'media', 'tags']);

        return Inertia::render('public/project/Show', [
            'project' => PublicProjectResource::make($project)->resolve(),
            'seo' => $seo->forProject($project),
        ]);
    }
}
