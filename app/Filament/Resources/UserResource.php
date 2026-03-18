<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([

                Tabs::make('Usuário')
                    ->columnSpanFull()
                    ->tabs([

                        Tab::make('Conta')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nome')
                                        ->required(),

                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->required()
                                        ->unique(ignoreRecord: true),

                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->dehydrated(fn ($state) => filled($state))
                                        ->required(fn ($context) => $context === 'create')
                                        ->minLength(8),

                                    Forms\Components\Select::make('roles')
                                        ->label('Perfis')
                                        ->multiple()
                                        ->relationship('roles', 'name')
                                        ->preload(),
                                ]),
                            ]),

                        Tab::make('Perfil')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('username')
                                        ->label('Username')
                                        ->prefix('mktcode.com.br/u/')
                                        ->unique(ignoreRecord: true)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($set, $state) => $set('username', Str::slug($state))),

                                    Forms\Components\TextInput::make('title')
                                        ->label('Título'),

                                    Forms\Components\TextInput::make('location')
                                        ->label('Localização'),
                                ]),
                            ]),

                        Tab::make('Bio')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\RichEditor::make('bio')
                                    ->label('Biografia')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Redes')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Repeater::make('social_links')
                                    ->label('Links')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->options([
                                                'github' => 'GitHub',
                                                'linkedin' => 'LinkedIn',
                                                'twitter' => 'Twitter',
                                                'instagram' => 'Instagram',
                                                'reddit' => 'Reddit',
                                                'tiktok' => 'TikTok',
                                                'website' => 'Website',
                                            ])
                                            ->required(),

                                        Forms\Components\TextInput::make('url')
                                            ->url()
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->reorderable(),
                            ]),

                        Tab::make('Mídia')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->label('Avatar')
                                    ->collection('profile_photo')
                                    ->avatar()
                                    ->image(),

                                Forms\Components\SpatieMediaLibraryFileUpload::make('cover_photo')
                                    ->label('Capa')
                                    ->collection('cover_photo')
                                    ->image()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->searchable(),

                    Tables\Columns\TextColumn::make('email')
                        ->size(TextSize::Small)
                        ->color('gray'),
                ]),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Perfis')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Posts')
                    ->formatStateUsing(fn ($state) => $state.' posts')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('projects_count')
                    ->counts('projects')
                    ->label('Projetos')
                    ->formatStateUsing(fn ($state) => $state.' projetos')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->label('Criado')
                    ->sortable(),
            ])

            ->defaultSort('created_at', 'desc')

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->groupedBulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
