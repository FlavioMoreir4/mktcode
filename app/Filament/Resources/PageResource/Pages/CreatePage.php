<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Domain\Content\Enums\PageStatus;
use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['published_at']) && ($data['status'] ?? null) === PageStatus::Published->value) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
