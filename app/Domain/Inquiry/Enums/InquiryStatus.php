<?php

declare(strict_types=1);

namespace App\Domain\Inquiry\Enums;

enum InquiryStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Archived = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::New => 'Novo',
            self::InProgress => 'Em atendimento',
            self::Resolved => 'Resolvido',
            self::Archived => 'Arquivado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New => 'info',
            self::InProgress => 'warning',
            self::Resolved => 'success',
            self::Archived => 'gray',
        };
    }
}
