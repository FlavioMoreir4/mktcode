<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Application\Inquiries\Queries\InquiryMetricsQuery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $metrics = app(InquiryMetricsQuery::class)->summary();

        return [
            Stat::make('Novas', $metrics['pending'])
                ->description($metrics['today'].' hoje')
                ->color('warning'),

            Stat::make('Em atendimento', $metrics['in_progress'])
                ->color('info'),

            Stat::make('Resolvidas', $metrics['resolved'])
                ->description("Taxa: {$metrics['resolution_rate']}%")
                ->color('success'),

            Stat::make('Atrasadas', $metrics['late'])
                ->description('> 24h sem resposta')
                ->color('danger'),

            Stat::make('Tempo médio', "{$metrics['average_response_time_hours']}h")
                ->color('primary'),

            Stat::make('SLA 24h', "{$metrics['sla_rate']}%")
                ->description('Resolvidas em até 24h')
                ->color($metrics['sla_rate'] >= 80 ? 'success' : 'danger'),
        ];
    }
}
