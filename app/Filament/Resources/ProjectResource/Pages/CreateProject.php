<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Application\Portfolio\DTOs\ProjectListingData;
use App\Application\Portfolio\Services\ProjectAdministrativeWorkflow;
use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected ProjectAdministrativeWorkflow $projectAdministrativeWorkflow;

    public function boot(ProjectAdministrativeWorkflow $projectAdministrativeWorkflow): void
    {
        $this->projectAdministrativeWorkflow = $projectAdministrativeWorkflow;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $project = new Project(Arr::except($data, ['status', 'user_id', 'featured', 'sort_order', 'screenshots', 'cover']));
        $project->save();

        $this->syncAdministrativeState($project, $data);

        return $project->refresh();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncAdministrativeState(Project $project, array $data): void
    {
        $this->projectAdministrativeWorkflow->sync(
            $project,
            ProjectListingData::fromArray($data),
            $data['status'] ?? null,
        );
    }
}
