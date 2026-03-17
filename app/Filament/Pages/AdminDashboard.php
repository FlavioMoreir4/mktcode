<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActivityChart;
use App\Filament\Widgets\InquiryStats;
use App\Filament\Widgets\LatestInquiries;
use Filament\Pages\Dashboard;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AdminDashboard extends Dashboard
{
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make(1)->schema(
                    $this->getWidgetsSchemaComponents([
                        InquiryStats::class,
                    ])
                ),

                Grid::make(1)->schema(
                    $this->getWidgetsSchemaComponents([
                        ActivityChart::class,
                    ])
                ),

                Grid::make(1)->schema(
                    $this->getWidgetsSchemaComponents([
                        LatestInquiries::class,
                    ])
                ),
            ]);
    }
}
