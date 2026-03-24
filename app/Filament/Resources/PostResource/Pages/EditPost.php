<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Application\Content\DTOs\PostPublicationData;
use App\Application\Content\Services\PostPublicationWorkflow;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected PostPublicationWorkflow $postPublicationWorkflow;

    public function boot(PostPublicationWorkflow $postPublicationWorkflow): void
    {
        $this->postPublicationWorkflow = $postPublicationWorkflow;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Post $record */
        $record->fill(Arr::except($data, ['status', 'published_at', 'tags', 'cover']));
        $record->save();

        $this->postPublicationWorkflow->sync($record, PostPublicationData::fromArray($data));

        return $record->refresh();
    }
}
