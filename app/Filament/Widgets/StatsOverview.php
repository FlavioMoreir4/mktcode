<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $resolvedInquiries = Inquiry::where('status', 'resolved')->count();

        $pending = Inquiry::where('status', 'new')->count();
        $inProgress = Inquiry::where('status', 'in_progress')->count();

        $todayInquiries = Inquiry::whereDate('created_at', today())->count();

        $resolutionRate = $pending + $inProgress + $resolvedInquiries > 0
            ? round(($resolvedInquiries / ($pending + $inProgress + $resolvedInquiries)) * 100)
            : 0;

        return [

            Stat::make('Novas', $pending)
                ->description("{$todayInquiries} hoje")
                ->color('warning'),

            Stat::make('Em atendimento', $inProgress)
                ->color('info'),

            Stat::make('Resolvidas', $resolvedInquiries)
                ->description("Taxa: {$resolutionRate}%")
                ->color('success'),
        ];
    }
}
