<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Serviços';

    protected static ?string $navigationGroup = 'Site';

    protected static ?string $modelLabel = 'Serviço';

    protected static ?string $pluralModelLabel = 'Serviços';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Serviço')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('icon')
                            ->label('Ícone (Lucide)')
                            ->options([
                                'monitor' => 'Monitor',
                                'layers' => 'Layers',
                                'layout' => 'Layout',
                                'globe' => 'Globe',
                                'search-code' => 'SearchCode',
                                'settings-2' => 'Settings2',
                                'brain' => 'Brain',
                                'code-2' => 'Code2',
                                'server' => 'Server',
                                'database' => 'Database',
                                'cpu' => 'Cpu',
                                'smartphone' => 'Smartphone',
                                'shield' => 'Shield',
                                'bar-chart' => 'BarChart',
                                'zap' => 'Zap',
                                'package' => 'Package',
                            ])
                            ->required()
                            ->default('monitor'),

                        Forms\Components\Textarea::make('ideal_for')
                            ->label('Ideal Para')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Funcionalidades Incluídas')
                    ->description('Liste os itens que estão incluídos neste serviço (aparecem como checkmarks na página).')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->label('Funcionalidades')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->label('Item')
                                    ->required()
                                    ->placeholder('Ex: Arquitetura pensada para escalar'),
                            ])
                            ->addActionLabel('Adicionar funcionalidade')
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Ativo no Site')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Ordem de Exibição')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ícone')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('features')
                    ->label('Funcionalidades')
                    ->formatStateUsing(fn ($state) => count((array) $state).' itens'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Ativos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
