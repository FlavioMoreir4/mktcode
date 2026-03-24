<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Commands;

use App\Models\Project;

class FeatureProject
{
    public function handle(Project $project, bool $featured): Project
    {
        $project->featured = $featured;
        $project->save();

        return $project->refresh();
    }
}
