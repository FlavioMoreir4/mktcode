<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Application\Inquiry\Queries\GetInquiryActivityChartQuery;
use Filament\Widgets\ChartWidget;

class ActivityChart extends ChartWidget
{
    protected ?string $heading = 'Atividade (7 dias)';

    protected ?string $maxHeight = '250px';

    private GetInquiryActivityChartQuery $chartQuery;

    public function boot(GetInquiryActivityChartQuery $chartQuery): void
    {
        $this->chartQuery = $chartQuery;
    }

    protected function getData(): array
    {
        $chartData = $this->chartQuery->forDays(7);

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
