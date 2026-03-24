<?php

declare(strict_types=1);

namespace App\Application\Inquiry\Queries;

use App\Application\Inquiry\DTOs\InquiryMetricsData;
use App\Domain\Inquiry\Contracts\InquiryRepository;
use App\Domain\Inquiry\Enums\InquiryStatus;

/**
 * Builds the dashboard-ready inquiry metrics outside of the Eloquent model.
 */
class GetInquiryMetricsQuery
{
    public function __construct(private readonly InquiryRepository $inquiries) {}

    public function summary(): InquiryMetricsData
    {
        $pending = $this->inquiries->countByStatus(InquiryStatus::New);
        $inProgress = $this->inquiries->countByStatus(InquiryStatus::InProgress);
        $resolved = $this->inquiries->countByStatus(InquiryStatus::Resolved);
        $today = $this->inquiries->countCreatedToday();
        $late = $this->inquiries->countLate();
        $resolvedWithinSla = $this->inquiries->resolvedWithinSlaCount();
        $total = $pending + $inProgress + $resolved;

        return new InquiryMetricsData(
            pending: $pending,
            inProgress: $inProgress,
            resolved: $resolved,
            today: $today,
            late: $late,
            total: $total,
            resolutionRate: $total > 0 ? (int) round(($resolved / $total) * 100) : 0,
            averageResponseTimeHours: $this->inquiries->averageResponseTimeHours(),
            resolvedWithinSla: $resolvedWithinSla,
            slaRate: $resolved > 0 ? (int) round(($resolvedWithinSla / $resolved) * 100) : 0,
        );
    }
}
