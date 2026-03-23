<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Application\Inquiries\Queries\InquiryMetricsQuery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryPerformance extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $metrics = app(InquiryMetricsQuery::class)->summary();

        return [
            Stat::make('Pendentes', $metrics['pending'])
                ->description('Aguardando atendimento')
                ->color('warning'),

            Stat::make('Atrasadas (+24h)', $metrics['late'])
                ->description('Fora do SLA')
                ->color('danger'),

            Stat::make('Tempo médio', "{$metrics['average_response_time_hours']}h")
                ->description('Resposta média')
                ->color('primary'),

            Stat::make('SLA 24h', "{$metrics['sla_rate']}%")
                ->description('Resolvidas em até 24h')
                ->color($metrics['sla_rate'] >= 80 ? 'success' : 'danger'),
        ];
    }
}
