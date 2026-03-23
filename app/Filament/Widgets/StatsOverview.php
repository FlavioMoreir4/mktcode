<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Application\Inquiries\Queries\InquiryMetricsQuery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $metrics = app(InquiryMetricsQuery::class)->summary();

        return [
            Stat::make('Novas', $metrics['pending'])
                ->description("{$metrics['today']} hoje")
                ->color('warning'),

            Stat::make('Em atendimento', $metrics['in_progress'])
                ->color('info'),

            Stat::make('Resolvidas', $metrics['resolved'])
                ->description("Taxa: {$metrics['resolution_rate']}%")
                ->color('success'),
        ];
    }
}
