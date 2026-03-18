<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryPerformance extends StatsOverviewWidget
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

        return [

            Stat::make('Pendentes', $counts->pending ?? 0)
                ->description('Aguardando atendimento')
                ->color('warning'),

            Stat::make('Atrasadas (+24h)', $counts->late ?? 0)
                ->description('Fora do SLA')
                ->color('danger'),

            Stat::make('Tempo médio', "{$avgResponseTime}h")
                ->description('Resposta média')
                ->color('primary'),

            Stat::make('SLA 24h', "{$slaRate}%")
                ->description('Resolvidas em até 24h')
                ->color($slaRate >= 80 ? 'success' : 'danger'),
        ];
    }
}
