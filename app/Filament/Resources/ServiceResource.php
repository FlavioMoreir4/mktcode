<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Serviços';

    protected static UnitEnum|string|null $navigationGroup = 'Site';

    protected static ?string $modelLabel = 'Serviço';

    protected static ?string $pluralModelLabel = 'Serviços';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([

                Section::make('Conteúdo do Serviço')
                    ->description('O que é, para quem é e qual valor entrega.')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->columnSpan(8)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(120),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->required()
                            ->rows(4)
                            ->placeholder('Explique claramente o valor do serviço...'),

                        Forms\Components\Textarea::make('ideal_for')
                            ->label('Ideal para')
                            ->rows(3)
                            ->placeholder('Ex: startups, e-commerces, SaaS...'),
                    ]),

                Grid::make(1)
                    ->columnSpan(4)
                    ->schema([

                        Section::make('Identidade')
                            ->icon('heroicon-o-sparkles')
                            ->compact()
                            ->schema([
                                Forms\Components\Select::make('icon')
                                    ->label('Ícone')
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
                                    ->default('monitor')
                                    ->native(false),
                            ]),

                        Section::make('Configuração')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->compact()
                            ->schema([
                                Forms\Components\Toggle::make('active')
                                    ->label('Ativo')
                                    ->helperText('Visível no site')
                                    ->default(true),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Ordem')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                Section::make('Funcionalidades')
                    ->description('O que está incluso (aparece como lista de benefícios).')
                    ->icon('heroicon-o-check-badge')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->label('Itens incluídos')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required()
                                    ->placeholder('Ex: Arquitetura escalável'),
                            ])
                            ->addActionLabel('Adicionar item')
                            ->reorderable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Stack::make([
                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->icon(fn ($record) => $record->active ? 'heroicon-o-check-circle' : 'heroicon-o-eye-slash')
                        ->iconColor(fn ($record) => $record->active ? 'success' : 'danger')
                        ->iconPosition(IconPosition::Before),
                ]),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => 'Ordem: '.$record->sort_order)
                    ->width(50),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->since()
                    ->sortable(),
            ])

            ->defaultSort('sort_order')
            ->reorderable('sort_order')

            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Ativos'),
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->groupedBulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->emptyStateHeading('Nenhum serviço ainda')
            ->emptyStateDescription('Crie serviços claros e orientados a valor.');
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
