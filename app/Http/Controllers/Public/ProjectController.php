<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicProjectCollection;
use App\Http\Resources\Public\PublicProjectResource;
use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $projects = Project::publicOrdered()
            ->with('media', 'tags')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('public/project/Index', [
            'projects' => new PublicProjectCollection($projects),
        ]);
    }

    public function show(Project $project): Response
    {
        $project->load('media', 'tags');

        return Inertia::render('public/project/Show', [
            'project' => PublicProjectResource::make($project)->resolve(),
        ]);
    }
}
