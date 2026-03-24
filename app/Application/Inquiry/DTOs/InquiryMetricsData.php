<?php

declare(strict_types=1);

namespace App\Application\Inquiry\DTOs;

final readonly class InquiryMetricsData
{
    public function __construct(
        public int $pending,
        public int $inProgress,
        public int $resolved,
        public int $today,
        public int $late,
        public int $total,
        public int $resolutionRate,
        public float $averageResponseTimeHours,
        public int $resolvedWithinSla,
        public int $slaRate,
    ) {}

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
    public function toArray(): array
    {
        return [
            'pending' => $this->pending,
            'in_progress' => $this->inProgress,
            'resolved' => $this->resolved,
            'today' => $this->today,
            'late' => $this->late,
            'total' => $this->total,
            'resolution_rate' => $this->resolutionRate,
            'average_response_time_hours' => $this->averageResponseTimeHours,
            'resolved_within_sla' => $this->resolvedWithinSla,
            'sla_rate' => $this->slaRate,
        ];
    }
}
