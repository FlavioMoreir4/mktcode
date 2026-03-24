<?php

declare(strict_types=1);

namespace App\Domain\Inquiry\Contracts;

use App\Application\Inquiry\DTOs\SubmitInquiryData;
use App\Domain\Inquiry\Enums\InquiryStatus;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Collection;

interface InquiryRepository
{
    public function create(SubmitInquiryData $data): Inquiry;

    public function save(Inquiry $inquiry): Inquiry;

    public function findOrFail(int $id): Inquiry;

    /**
     * @return Collection<int, Inquiry>
     */
    public function latestPrioritized(int $limit = 5): Collection;

    public function countByStatus(InquiryStatus $status): int;

    public function countCreatedToday(): int;

    public function countLate(): int;

    public function averageResponseTimeHours(): float;

    public function resolvedWithinSlaCount(): int;

    public function totalResolved(): int;

    /**
     * @return array<string, int>
     */
    public function countsByDate(int $days): array;
}
