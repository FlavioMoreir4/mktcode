<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Application\Inquiry\Queries\GetInquiryMetricsQuery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryStats extends StatsOverviewWidget
{
    private GetInquiryMetricsQuery $metricsQuery;

    public function boot(GetInquiryMetricsQuery $metricsQuery): void
    {
        $this->metricsQuery = $metricsQuery;
    }

    protected function getStats(): array
    {
        $metrics = $this->metricsQuery->summary();

        return [
            Stat::make('Novas', $metrics->pending)
                ->description($metrics->today.' hoje')
                ->color('warning'),

            Stat::make('Em atendimento', $metrics->inProgress)
                ->color('info'),

            Stat::make('Resolvidas', $metrics->resolved)
                ->description("Taxa: {$metrics->resolutionRate}%")
                ->color('success'),

            Stat::make('Atrasadas', $metrics->late)
                ->description('> 24h sem resposta')
                ->color('danger'),

            Stat::make('Tempo médio', "{$metrics->averageResponseTimeHours}h")
                ->color('primary'),

            Stat::make('SLA 24h', "{$metrics->slaRate}%")
                ->description('Resolvidas em até 24h')
                ->color($metrics->slaRate >= 80 ? 'success' : 'danger'),
        ];
    }
}
