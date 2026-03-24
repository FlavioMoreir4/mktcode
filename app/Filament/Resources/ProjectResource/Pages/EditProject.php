<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Application\Portfolio\DTOs\ProjectListingData;
use App\Application\Portfolio\Services\ProjectAdministrativeWorkflow;
use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected ProjectAdministrativeWorkflow $projectAdministrativeWorkflow;

    public function boot(ProjectAdministrativeWorkflow $projectAdministrativeWorkflow): void
    {
        $this->projectAdministrativeWorkflow = $projectAdministrativeWorkflow;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Project $record */
        $record->fill(Arr::except($data, ['status', 'user_id', 'featured', 'sort_order', 'screenshots', 'cover']));
        $record->save();

        $this->syncAdministrativeState($record, $data);

        return $record->refresh();
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
