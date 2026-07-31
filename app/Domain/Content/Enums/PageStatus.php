<?php

declare(strict_types=1);

namespace App\Domain\Content\Enums;

enum PageStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Published => 'Publicado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
        };
    }
}
