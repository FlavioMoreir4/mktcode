<?php

declare(strict_types=1);

namespace App\Application\Inquiries\Queries;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;

/**
 * Read model used by Filament widgets to keep dashboard analytics out of the Eloquent model.
 */
class InquiryMetricsQuery
{
    /**
     * @return array{
     *     pending: int,
     *     in_progress: int,
     *     resolved: int,
     *     today: int,
     *     late: int,
     *     total: int,
     *     resolution_rate: int,
     *     average_response_time_hours: float,
     *     resolved_within_sla: int,
     *     sla_rate: int
     * }
     */
    public function summary(): array
    {
        $pending = Inquiry::query()->status(InquiryStatus::New)->count();
        $inProgress = Inquiry::query()->status(InquiryStatus::InProgress)->count();
        $resolved = Inquiry::query()->status(InquiryStatus::Resolved)->count();
        $today = Inquiry::query()->today()->count();
        $late = Inquiry::query()->late()->count();
        $resolvedWithinSla = $this->resolvedWithinSlaCount();
        $total = $pending + $inProgress + $resolved;

        return [
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'today' => $today,
            'late' => $late,
            'total' => $total,
            'resolution_rate' => $total > 0
                ? (int) round(($resolved / $total) * 100)
                : 0,
            'average_response_time_hours' => $this->averageResponseTimeHours(),
            'resolved_within_sla' => $resolvedWithinSla,
            'sla_rate' => $resolved > 0
                ? (int) round(($resolvedWithinSla / $resolved) * 100)
                : 0,
        ];
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
