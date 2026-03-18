<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $counts = Inquiry::getDashboardCounts();
        $avgResponseTime = Inquiry::getAvgResponseTime();
        $sla = Inquiry::resolved()->metSla()->count();
        $totalResolved = Inquiry::resolved()->count();

        $slaRate = $totalResolved > 0
            ? round(($sla / $totalResolved) * 100)
            : 0;

        $total = ($counts->pending ?? 0)
            + ($counts->in_progress ?? 0)
            + ($counts->resolved ?? 0);

        $resolutionRate = $total > 0
            ? round(($counts->resolved / $total) * 100)
            : 0;

        return [

            Stat::make('Novas', $counts->pending ?? 0)
                ->description(($counts->today ?? 0).' hoje')
                ->color('warning'),

            Stat::make('Em atendimento', $counts->in_progress ?? 0)
                ->color('info'),

            Stat::make('Resolvidas', $counts->resolved ?? 0)
                ->description("Taxa: {$resolutionRate}%")
                ->color('success'),

            Stat::make('Atrasadas', $counts->late ?? 0)
                ->description('> 24h sem resposta')
                ->color('danger'),

            Stat::make('Tempo médio', "{$avgResponseTime}h")
                ->color('primary'),

            Stat::make('SLA 24h', "{$slaRate}%")
                ->description('Resolvidas em até 24h')
                ->color($slaRate >= 80 ? 'success' : 'danger'),
        ];
    }
}
