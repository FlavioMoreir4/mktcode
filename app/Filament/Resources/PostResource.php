<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Post';

    protected static ?string $pluralModelLabel = 'Posts';

    protected static ?string $navigationLabel = 'Blog';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([

                Section::make('Conteúdo Principal')
                    ->description('Título, narrativa e valor entregue.')
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
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL amigável, editável'),

                        Forms\Components\Textarea::make('excerpt')
                            ->label('Resumo')
                            ->rows(3)
                            ->placeholder('Resumo curto para listagens...'),

                        Forms\Components\RichEditor::make('body')
                            ->label('Conteúdo completo')
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
                                    ->options([
                                        'draft' => 'Rascunho',
                                        'published' => 'Publicado',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->native(false),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publicar em')
                                    ->helperText('Se for no futuro, será agendado automaticamente'),

                                Forms\Components\Select::make('author_id')
                                    ->label('Autor')
                                    ->relationship('author', 'name')
                                    ->default(auth()->id())
                                    ->searchable(),
                            ]),

                        Section::make('Taxonomia')
                            ->icon('heroicon-o-tag')
                            ->compact()
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Categoria')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),
                                        Forms\Components\Hidden::make('slug')
                                            ->required(),
                                    ]),

                                SpatieTagsInput::make('tags')
                                    ->label('Tags'),
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
                                    ->label('Imagem de capa')
                                    ->collection('cover')
                                    ->image()
                                    ->imageEditor(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->width(70)
                    ->height(50)
                    ->label(''),

                Stack::make([
                    Tables\Columns\TextColumn::make('title')
                        ->label('Título')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->icon(fn ($record) => match (true) {
                            $record->status === 'draft' => 'heroicon-o-pencil',
                            $record->published_at && $record->published_at->isFuture() => 'heroicon-o-clock',
                            default => 'heroicon-o-check-circle',
                        })
                        ->iconPosition(IconPosition::After),

                    Tables\Columns\TextColumn::make('category.name')
                        ->label('Categoria')
                        ->icon('heroicon-o-tag')
                        ->size(TextSize::Small),

                    Tables\Columns\TextColumn::make('author.name')
                        ->label('Autor')
                        ->icon('heroicon-o-user')
                        ->size(TextSize::ExtraSmall),
                ]),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => match (true) {
                        $record->status === 'draft' => 'Rascunho',
                        $record->published_at && $record->published_at->isFuture() => 'Agendado',
                        default => 'Publicado',
                    })
                    ->color(fn ($record) => match (true) {
                        $record->status === 'draft' => 'gray',
                        $record->published_at && $record->published_at->isFuture() => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicação')
                    ->since()
                    ->sortable(),
            ])

            ->recordActions([
                Action::make('preview')
                    ->label('Visualizar')
                    ->url(fn (Post $record) => route('public.blog.show', $record->slug))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-eye'),

                Action::make('edit')
                    ->label('Editar')
                    ->url(fn (Post $record): string => self::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil'),

                Action::make('delete')
                    ->label('Excluir')
                    ->action(fn (Post $record) => $record->delete())
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])

            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
            ])

            ->defaultSort('published_at', 'desc')

            ->emptyStateHeading('Nenhum post ainda')
            ->emptyStateDescription('Comece escrevendo algo que valha a pena ser lido.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
