<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\ChartWidget;

class ActivityChart extends ChartWidget
{
    protected ?string $heading = 'Atividade (7 dias)';

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $chartData = Inquiry::getActivityChartData(7);

        return [
            'datasets' => [
                [
                    'label' => 'Mensagens',
                    'data' => $chartData['values'],
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
