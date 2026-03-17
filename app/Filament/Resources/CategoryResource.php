<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelLabel = 'Categoria';

    protected static UnitEnum|string|null $navigationGroup = 'Site';

    protected static ?string $pluralModelLabel = 'Categorias';

    protected static ?string $navigationLabel = 'Categorias';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([

                Section::make('Informações da Categoria')
                    ->description('Organiza os conteúdos do blog.')
                    ->icon('heroicon-o-tag')
                    ->columnSpan(8)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Usado na URL (ex: /categoria/tecnologia)'),
                    ]),

                Grid::make(1)
                    ->columnSpan(4)
                    ->schema([
                        Section::make('Resumo')
                            ->icon('heroicon-o-chart-bar')
                            ->compact()
                            ->schema([
                                TextEntry::make('posts_count')
                                    ->label('Posts vinculados')
                                    ->aboveContent(fn ($record) => $record?->posts()->count() > 0 ? $record?->posts()->count().' posts' : 'Nenhum post vinculado'),

                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label('Categoria')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large),

                    Tables\Columns\TextColumn::make('slug')
                        ->size(TextSize::Small)
                        ->color('gray'),
                ]),

                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts')
                    ->formatStateUsing(fn ($state) => $state.' posts')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->since()
                    ->sortable(),
            ])

            ->defaultSort('name')

            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])

            ->groupedBulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->emptyStateHeading('Nenhuma categoria criada')
            ->emptyStateDescription('Crie categorias para organizar melhor os conteúdos do blog.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
