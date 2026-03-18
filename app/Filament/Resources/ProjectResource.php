<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $modelLabel = 'Projeto';

    protected static ?string $pluralModelLabel = 'Projetos';

    protected static ?string $navigationLabel = 'Projetos';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([

                Section::make('Conteúdo Principal')
                    ->description('Aqui vive a alma do projeto — título, narrativa e impacto.')
                    ->icon('heroicon-o-document-text')
                    ->columnSpan(8)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state)))
                            ->maxLength(120),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL amigável gerada automaticamente (editável)'),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição Curta')
                            ->rows(3)
                            ->placeholder('Resumo objetivo do projeto...'),

                        Forms\Components\RichEditor::make('content')
                            ->label('Case completo')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Grid::make(1)
                    ->columnSpan(4)
                    ->schema([

                        Section::make('Publicação')
                            ->icon('heroicon-o-rocket-launch')
                            ->compact()
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(ProjectStatus::class)
                                    ->default(ProjectStatus::Draft)
                                    ->required()
                                    ->native(false),

                                Forms\Components\Toggle::make('featured')
                                    ->label('Destaque')
                                    ->helperText('Exibir na home'),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Ordem')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Section::make('Informações')
                            ->icon('heroicon-o-building-office')
                            ->compact()
                            ->schema([
                                Forms\Components\TextInput::make('client')
                                    ->label('Cliente'),

                                Forms\Components\TextInput::make('year')
                                    ->label('Ano')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue((int) date('Y') + 1),

                                Forms\Components\TagsInput::make('stack')
                                    ->label('Stack'),

                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->url(),
                            ]),

                        Section::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->compact()
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('Título SEO')
                                    ->maxLength(60),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('Descrição SEO')
                                    ->rows(2)
                                    ->maxLength(160),
                            ]),

                        Section::make('Mídia')
                            ->icon('heroicon-o-photo')
                            ->compact()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('cover')
                                    ->label('Capa')
                                    ->collection('cover')
                                    ->image()
                                    ->imageEditor(),

                                SpatieMediaLibraryFileUpload::make('screenshots')
                                    ->label('Galeria')
                                    ->collection('screenshots')
                                    ->multiple()
                                    ->reorderable()
                                    ->image(),
                            ]),
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
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        // Ícone só aparece se for destaque
                        ->icon(fn (Project $record) => $record->featured ? 'heroicon-o-star' : null)
                        ->iconColor(fn (Project $record) => $record->featured ? 'warning' : null)
                        ->iconPosition(IconPosition::After),

                    Tables\Columns\TextColumn::make('client')
                        ->icon('heroicon-o-building-office-2')
                        ->size(TextSize::Small),

                    Tables\Columns\TextColumn::make('year')
                        ->icon('heroicon-o-calendar')
                        ->size(TextSize::ExtraSmall),
                ]),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (ProjectStatus $state): string => $state->getLabel())
                    ->color(fn (ProjectStatus $state): string => $state->getColor()),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Project $record): string => self::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil'),

                Action::make('delete')
                    ->action(fn (Project $record) => $record->delete())
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
            ])
            ->emptyStateHeading('Nenhum projeto ainda')
            ->emptyStateDescription('Crie seu primeiro case e comece a construir autoridade.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
