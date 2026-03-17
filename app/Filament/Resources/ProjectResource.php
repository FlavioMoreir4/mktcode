<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $modelLabel = 'Projeto';

    protected static ?string $pluralModelLabel = 'Projetos';

    protected static ?string $navigationLabel = 'Projetos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Detalhes do Projeto')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Título')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Descrição Curta')
                                            ->rows(3),
                                        TiptapEditor::make('content')
                                            ->label('Conteúdo/Case Study')
                                            ->required()
                                            ->profile('default')
                                            ->output(TiptapOutput::Json)
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Section::make('Mídia')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('cover')
                                            ->label('Capa do Projeto')
                                            ->collection('cover')
                                            ->image(),
                                        SpatieMediaLibraryFileUpload::make('screenshots')
                                            ->label('Screenshots/Galeria')
                                            ->collection('screenshots')
                                            ->multiple()
                                            ->reorderable()
                                            ->image(),
                                    ]),
                            ])
                            ->columnSpan(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Metadados')
                                    ->schema([
                                        Forms\Components\TextInput::make('client')
                                            ->label('Cliente'),
                                        Forms\Components\TextInput::make('year')
                                            ->label('Ano'),
                                        Forms\Components\Select::make('status')
                                            ->options(['draft' => 'Rascunho', 'published' => 'Publicado'])
                                            ->default('draft')
                                            ->required(),
                                        Forms\Components\TagsInput::make('stack')
                                            ->label('Tecnologias (Stack)'),
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL do Projeto')
                                            ->url(),
                                        Forms\Components\Toggle::make('featured')
                                            ->label('Destaque na Home'),
                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Ordem')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                                Forms\Components\Section::make('SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('seo_title')
                                            ->label('Título SEO'),
                                        Forms\Components\Textarea::make('seo_description')
                                            ->label('Descrição SEO')
                                            ->rows(2),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Ano')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('url')
                    ->searchable(),
                Tables\Columns\IconColumn::make('featured')
                    ->label('Destaque')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seo_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
