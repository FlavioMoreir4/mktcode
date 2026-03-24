<?php

declare(strict_types=1);

namespace App\Infrastructure\Inquiry\Persistence\Eloquent;

use App\Application\Inquiry\DTOs\SubmitInquiryData;
use App\Domain\Inquiry\Contracts\InquiryRepository;
use App\Domain\Inquiry\Enums\InquiryStatus;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Collection;

class EloquentInquiryRepository implements InquiryRepository
{
    public function create(SubmitInquiryData $data): Inquiry
    {
        $inquiry = Inquiry::query()->create($data->toArray());

        return $inquiry->refresh();
    }

    public function save(Inquiry $inquiry): Inquiry
    {
        $inquiry->save();

        return $inquiry->refresh();
    }

    public function findOrFail(int $id): Inquiry
    {
        return Inquiry::query()->findOrFail($id);
    }

    public function latestPrioritized(int $limit = 5): Collection
    {
        return Inquiry::query()
            ->orderByRaw(
                'CASE WHEN status = ? THEN 0 ELSE 1 END',
                [InquiryStatus::New->value]
            )
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function countByStatus(InquiryStatus $status): int
    {
        return Inquiry::query()->status($status)->count();
    }

    public function countCreatedToday(): int
    {
        return Inquiry::query()->today()->count();
    }

    public function countLate(): int
    {
        return Inquiry::query()->late()->count();
    }

    public function averageResponseTimeHours(): float
    {
        $avg = Inquiry::query()
            ->resolved()
            ->selectRaw($this->averageResolutionHoursExpression().' as avg_time')
            ->value('avg_time');

        return round((float) $avg, 1);
    }

    public function resolvedWithinSlaCount(): int
    {
        return Inquiry::query()
            ->resolved()
            ->whereRaw($this->resolvedWithinSlaExpression())
            ->count();
    }

    public function totalResolved(): int
    {
        return Inquiry::query()->resolved()->count();
    }

    public function countsByDate(int $days): array
    {
        $dateExpression = $this->usesSqlite()
            ? "strftime('%Y-%m-%d', created_at)"
            : 'DATE(created_at)';

        return Inquiry::query()
            ->selectRaw("{$dateExpression} as date, COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date')
            ->map(fn (mixed $total): int => (int) $total)
            ->all();
    }

    private function averageResolutionHoursExpression(): string
    {
        if ($this->usesSqlite()) {
            return "AVG((strftime('%s', updated_at) - strftime('%s', created_at)) / 3600.0)";
        }

        return 'AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at))';
    }

    private function resolvedWithinSlaExpression(): string
    {
        if ($this->usesSqlite()) {
            return "(strftime('%s', updated_at) - strftime('%s', created_at)) <= 86400";
        }

        return 'TIMESTAMPDIFF(HOUR, created_at, updated_at) <= 24';
    }

    private function usesSqlite(): bool
    {
        return Inquiry::query()->getConnection()->getDriverName() === 'sqlite';
    }
}
