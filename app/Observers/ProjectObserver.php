<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Artisan;

class ProjectObserver
{
    public function saved(Project $project): void
    {
        if ($project->status === 'published') {
            Artisan::queue('app:generate-sitemap');
        }
    }

    public function deleted(Project $project): void
    {
        Artisan::queue('app:generate-sitemap');
    }
}
