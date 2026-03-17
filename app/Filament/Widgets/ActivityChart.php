<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\ChartWidget;

class ActivityChart extends ChartWidget
{
    protected ?string $heading = 'Atividade (7 dias)';

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        // 🔥 1 query só (agrupada)
        $data = Inquiry::selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total
            ')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        // 📊 garantir 7 dias completos (mesmo sem dados)
        $labels = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('d/m');
        });

        $values = collect(range(6, 0))->map(function ($i) use ($data) {
            $date = now()->subDays($i)->toDateString();

            return $data[$date] ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Mensagens',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
