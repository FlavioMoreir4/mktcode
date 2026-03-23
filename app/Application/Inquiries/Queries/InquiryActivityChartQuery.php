<?php

declare(strict_types=1);

namespace App\Application\Inquiries\Queries;

use App\Models\Inquiry;

/**
 * Builds chart-ready inquiry activity series in a database-portable way.
 */
class InquiryActivityChartQuery
{
    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    public function forDays(int $days = 7): array
    {
        $dateExpression = $this->usesSqlite()
            ? "strftime('%Y-%m-%d', created_at)"
            : 'DATE(created_at)';

        $data = Inquiry::query()
            ->selectRaw("{$dateExpression} as date, COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

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

    private function usesSqlite(): bool
    {
        return Inquiry::query()->getConnection()->getDriverName() === 'sqlite';
    }
}
