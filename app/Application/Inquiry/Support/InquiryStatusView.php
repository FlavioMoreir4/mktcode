<?php

declare(strict_types=1);

namespace App\Application\Inquiry\Support;

use App\Domain\Inquiry\Enums\InquiryStatus;

final readonly class InquiryStatusView
{
    public function __construct(
        public string $label,
        public string $color,
        public ?string $rowClass = null,
    ) {}

    public static function from(InquiryStatus $status): self
    {
        return match ($status) {
            InquiryStatus::New => new self(
                label: $status->getLabel() ?? $status->value,
                color: (string) ($status->getColor() ?? 'gray'),
                rowClass: 'bg-gray-50 dark:bg-gray-800/40',
            ),
            default => new self(
                label: $status->getLabel() ?? $status->value,
                color: (string) ($status->getColor() ?? 'gray'),
            ),
        };
    }
}
