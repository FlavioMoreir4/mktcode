<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Content\Enums\PageStatus;
use App\Filament\Resources\Concerns\HasDynamicRichEditor;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    use HasDynamicRichEditor;

    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document';

    protected static ?string $modelLabel = 'Página';

    protected static ?string $pluralModelLabel = 'Páginas';

    protected static ?string $navigationLabel = 'Páginas';

    protected static ?int $navigationSort = 60;

    public static function resolveDisplayStatus(Page $page): PageStatus
    {
        if ($page->published_at?->isFuture() && $page->status === PageStatus::Published) {
            return PageStatus::Published;
        }

        return $page->status;
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Conteúdo')
                    ->icon('heroicon-o-document-text')
                    ->columnSpan(8)
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state)))
                            ->maxLength(120),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL amigável, editável. Ex: política-de-privacidade'),

                        Textarea::make('excerpt')
                            ->label('Resumo')
                            ->rows(3)
                            ->placeholder('Resumo curto para SEO / compartilhamento...'),

                        self::getFullRichEditor('body')
                            ->label('Conteúdo')
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
                                    ->options(PageStatus::class)
                                    ->default(PageStatus::Draft)
                                    ->required()
                                    ->native(false),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publicar em')
                                    ->helperText('Se vazio, usa a data de criação/atualização.'),
                            ]),

                        Section::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->compact()
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Título SEO')
                                    ->maxLength(60),

                                Textarea::make('seo_description')
                                    ->label('Descrição SEO')
                                    ->rows(2)
                                    ->maxLength(160),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('title')
                        ->label('Título')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->icon(fn (Page $record) => match (self::resolveDisplayStatus($record)) {
                            PageStatus::Published => 'heroicon-o-check-circle',
                            default => 'heroicon-o-pencil',
                        })
                        ->iconPosition(IconPosition::After),

                    TextColumn::make('slug')
                        ->label('Slug')
                        ->size(TextSize::Small)
                        ->color('gray'),
                ]),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (Page $record): string => self::resolveDisplayStatus($record)->getLabel())
                    ->color(fn (Page $record): string|array => self::resolveDisplayStatus($record)->getColor() ?? 'gray'),

                TextColumn::make('published_at')
                    ->label('Publicação')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Visualizar')
                    ->url(fn (Page $record) => route('public.page.show', $record->slug))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-eye'),

                Action::make('edit')
                    ->label('Editar')
                    ->url(fn (Page $record): string => self::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil'),

                Action::make('delete')
                    ->label('Excluir')
                    ->action(fn (Page $record) => $record->delete())
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])
            ->defaultSort('published_at', 'desc')
            ->emptyStateHeading('Nenhuma página ainda')
            ->emptyStateDescription('Crie páginas institucionais como Política de Privacidade e Termos de Serviço.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
