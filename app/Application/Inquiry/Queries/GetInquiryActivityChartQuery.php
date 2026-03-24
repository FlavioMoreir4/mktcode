<?php

declare(strict_types=1);

namespace App\Application\Inquiry\Queries;

use App\Domain\Inquiry\Contracts\InquiryRepository;

/**
 * Produces chart-ready activity data from the inquiry read model.
 */
class GetInquiryActivityChartQuery
{
    public function __construct(private readonly InquiryRepository $inquiries) {}

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    public function forDays(int $days = 7): array
    {
        $data = $this->inquiries->countsByDate($days);

        $labels = collect(range($days - 1, 0))
            ->map(fn (int $offset): string => now()->subDays($offset)->format('d/m'))
            ->toArray();

        $values = collect(range($days - 1, 0))
            ->map(fn (int $offset): int => (int) ($data[now()->subDays($offset)->toDateString()] ?? 0))
            ->toArray();

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
