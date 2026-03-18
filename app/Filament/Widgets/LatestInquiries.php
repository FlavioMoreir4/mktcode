<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestInquiries extends TableWidget
{
    protected static ?string $heading = 'Mensagens Recentes';

    protected static ?int $sort = 1;

    protected function getTablePollingInterval(): ?string
    {
        return '10s';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inquiry::prioritizeNew()
                ->latest()
                ->limit(5)
            )

            ->columns([

                TextColumn::make('name')
                    ->label('Contato')
                    ->weight('bold')
                    ->searchable(false),

                TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (Inquiry $record): string => $record->getStatusLabel())
                    ->color(fn (Inquiry $record): string => $record->getStatusColor()),

                TextColumn::make('created_at')
                    ->label('Recebido')
                    ->since(),
            ])

            ->recordClasses(fn (Inquiry $record) => $record->status === 'new'
                    ? 'bg-gray-50 dark:bg-gray-800/40'
                    : null
            )

            ->recordUrl(fn (Inquiry $record): string => route('filament.admin.resources.inquiries.view', $record)
            )

            ->recordActions([

                Action::make('atender')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->visible(fn (Inquiry $record) => $record->status === 'new')
                    ->action(fn (Inquiry $record) => $record->update(['status' => 'in_progress'])
                    ),

                Action::make('resolver')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Inquiry $record) => $record->status !== 'resolved')
                    ->action(fn (Inquiry $record) => $record->update(['status' => 'resolved'])
                    ),

                ViewAction::make()
                    ->schema([
                        Grid::make(2)->schema([
                            Group::make([
                                TextEntry::make('name')->label('Nome'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(fn (Inquiry $record): string => $record->getStatusLabel())
                                    ->color(fn (Inquiry $record): string => $record->getStatusColor()),
                            ]),
                            Group::make([
                                TextEntry::make('email')->label('Email'),
                                TextEntry::make('whatsapp')->label('WhatsApp'),
                            ]),
                        ]),

                        Grid::make(1)->schema([
                            TextEntry::make('message')
                                ->label('Mensagem')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ])

            ->emptyStateHeading('Nenhuma mensagem recente')
            ->emptyStateDescription('As novas mensagens aparecerão aqui automaticamente.');
    }
}
