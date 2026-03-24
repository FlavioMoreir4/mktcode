<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Services;

use App\Application\Portfolio\Commands\AssignProjectOwner;
use App\Application\Portfolio\Commands\FeatureProject;
use App\Application\Portfolio\Commands\PublishProject;
use App\Application\Portfolio\Commands\ReorderProjects;
use App\Application\Portfolio\Commands\UnpublishProject;
use App\Application\Portfolio\DTOs\ProjectListingData;
use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\Project;

class ProjectAdministrativeWorkflow
{
    public function __construct(
        private readonly AssignProjectOwner $assignProjectOwner,
        private readonly FeatureProject $featureProject,
        private readonly PublishProject $publishProject,
        private readonly ReorderProjects $reorderProjects,
        private readonly UnpublishProject $unpublishProject,
    ) {}

    public function sync(Project $project, ProjectListingData $listing, ProjectStatus|string|null $status): void
    {
        $resolvedStatus = $status instanceof ProjectStatus
            ? $status
            : ProjectStatus::from((string) ($status ?? ProjectStatus::Draft->value));

        $this->assignProjectOwner->handle($project, $listing->ownerId);
        $this->featureProject->handle($project, $listing->featured);
        $this->reorderProjects->handle($project, $listing->sortOrder);

        if ($resolvedStatus === ProjectStatus::Published) {
            $this->publishProject->handle($project);

            return;
        }

        $this->unpublishProject->handle($project);
    }
}
