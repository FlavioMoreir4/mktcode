<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $driver = config('database.default');

        // 🔥 1 QUERY BASE (status + atrasadas)
        $counts = Inquiry::selectRaw("
                SUM(status = 'new') as pending,
                SUM(status = 'in_progress') as in_progress,
                SUM(status = 'resolved') as resolved,
                SUM(DATE(created_at) = DATE('now')) as today,
                SUM(status = 'new' AND created_at < ?) as late
            ", [now()->subHours(24)])
            ->first();

        // 🔥 tempo médio
        $avgResponseTime = Inquiry::whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at')
            ->when($driver === 'sqlite', fn ($q) => $q->selectRaw("
                AVG((strftime('%s', updated_at) - strftime('%s', created_at)) / 3600.0)
            "))
            ->when($driver !== 'sqlite', fn ($q) => $q->selectRaw('
                AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at))
            '))
            ->value('avg');

        $avgResponseTime = round($avgResponseTime ?? 0, 1);

        // 🔥 SLA
        $sla = Inquiry::whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at')
            ->where(function ($q) use ($driver) {
                if ($driver === 'sqlite') {
                    $q->whereRaw("
                        (strftime('%s', updated_at) - strftime('%s', created_at)) <= 86400
                    ");
                } else {
                    $q->whereRaw('
                        TIMESTAMPDIFF(HOUR, created_at, updated_at) <= 24
                    ');
                }
            })
            ->count();

        $totalResolved = Inquiry::whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at')
            ->count();

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

            // 📊 VOLUME
            Stat::make('Novas', $counts->pending ?? 0)
                ->description(($counts->today ?? 0).' hoje')
                ->color('warning'),

            Stat::make('Em atendimento', $counts->in_progress ?? 0)
                ->color('info'),

            Stat::make('Resolvidas', $counts->resolved ?? 0)
                ->description("Taxa: {$resolutionRate}%")
                ->color('success'),

            // ⚡ PERFORMANCE
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
