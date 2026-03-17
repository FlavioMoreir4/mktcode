<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $projects = Project::with('media')
            ->published()
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        $projects->getCollection()->transform(function ($project) {
            $project->content = RichContentRenderer::make($project->content)->toHtml();

            return $project;
        });

        return Inertia::render('public/project/Index', [
            'projects' => $projects,
        ]);
    }

    public function show(Project $project): Response
    {
        $project->load('media');
        $project->content = RichContentRenderer::make($project->content)->toHtml();

        return Inertia::render('public/project/Show', [
            'project' => $project,
        ]);
    }
}
