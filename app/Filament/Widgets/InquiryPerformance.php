<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryPerformance extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // 🔥 1 query para contadores
        $counts = Inquiry::selectRaw("
                SUM(status = 'new') as pending,
                SUM(status = 'new' AND created_at < ?) as late
            ", [now()->subHours(24)])
            ->first();

        $driver = config('database.default');

        // 🔥 tempo médio (corrigido e mais preciso)
        $avgResponseTime = Inquiry::whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at') // evita lixo
            ->when($driver === 'sqlite', fn ($q) => $q->selectRaw("
                AVG((strftime('%s', updated_at) - strftime('%s', created_at)) / 3600.0)
            "))
            ->when($driver !== 'sqlite', fn ($q) => $q->selectRaw('
                AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at))
            '))
            ->value('avg');

        $avgResponseTime = round($avgResponseTime ?? 0, 1);

        // 🎯 SLA (% resolvido em até 24h)
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
