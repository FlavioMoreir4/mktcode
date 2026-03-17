<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestUsers extends TableWidget
{
    protected static ?string $heading = 'Usuários Recentes';

    protected static ?int $sort = 2;

    protected function getTablePollingInterval(): ?string
    {
        return '15s';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query()
                ->latest()
                ->limit(5)
            )

            ->columns([

                TextColumn::make('name')
                    ->label('Nome')
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->color('gray')
                    ->limit(30),

                TextColumn::make('created_at')
                    ->label('Criado')
                    ->since(),
            ])

            // 🔗 clique na linha → vai pro resource
            ->recordUrl(fn (User $record): string => route('filament.admin.resources.users.view', $record)
            )

            ->recordActions([

                Action::make('email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->url(fn (User $record): string => "mailto:{$record->email}")
                    ->openUrlInNewTab(),

                ViewAction::make()
                    ->schema([
                        Grid::make(2)->schema([

                            Group::make([
                                TextEntry::make('name')->label('Nome'),
                                TextEntry::make('email')->label('Email'),
                            ]),

                            Group::make([
                                TextEntry::make('created_at')
                                    ->label('Criado em')
                                    ->since(),
                            ]),
                        ]),
                    ]),
            ])

            ->emptyStateHeading('Nenhum usuário recente')
            ->emptyStateDescription('Novos usuários aparecerão aqui automaticamente.');
    }
}
