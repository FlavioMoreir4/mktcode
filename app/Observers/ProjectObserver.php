<?php

declare(strict_types=1);

namespace App\Observers;

use App\Console\Commands\GenerateSitemap;
use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Support\Facades\Artisan;

/**
 * Regenerates public search metadata whenever a public project changes.
 */
class ProjectObserver
{
    public function saved(Project $project): void
    {
        if ($project->status === ProjectStatus::Published) {
            Artisan::queue(GenerateSitemap::SIGNATURE);
        }
    }

    public function deleted(Project $project): void
    {
        Artisan::queue(GenerateSitemap::SIGNATURE);
    }
}
