<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Application\Content\DTOs\PostPublicationData;
use App\Application\Content\Services\PostPublicationWorkflow;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected PostPublicationWorkflow $postPublicationWorkflow;

    public function boot(PostPublicationWorkflow $postPublicationWorkflow): void
    {
        $this->postPublicationWorkflow = $postPublicationWorkflow;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $post = new Post(Arr::except($data, ['status', 'published_at', 'tags', 'cover']));
        $post->save();

        $this->postPublicationWorkflow->sync($post, PostPublicationData::fromArray($data));

        return $post->refresh();
    }
}
