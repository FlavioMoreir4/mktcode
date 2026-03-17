<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    protected static ?string $navigationLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Conta')
                            ->icon('heroicon-m-user-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->label('Senha')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->maxLength(255),
                                Forms\Components\Select::make('roles')
                                    ->label('Funções/Perfis')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload(),
                            ])->columns(2),
                        Tabs\Tab::make('Perfil')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                Forms\Components\TextInput::make('username')
                                    ->label('Nome de Usuário (Slug)')
                                    ->unique(ignoreRecord: true)
                                    ->prefix('mktcode.com.br/u/')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('title')
                                    ->label('Título Profissional')
                                    ->placeholder('Ex: Desenvolvedor Senior / Designer UX')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('location')
                                    ->label('Localização')
                                    ->placeholder('Ex: São Paulo, Brasil')
                                    ->maxLength(255),
                            ])->columns(2),
                        Tabs\Tab::make('Bio')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                TiptapEditor::make('bio')
                                    ->label('Biografia')
                                    ->profile('default')
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Redes Sociais')
                            ->icon('heroicon-m-share')
                            ->schema([
                                Repeater::make('social_links')
                                    ->label('Links Sociais')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->label('Plataforma')
                                            ->options([
                                                'github' => 'GitHub',
                                                'linkedin' => 'LinkedIn',
                                                'twitter' => 'Twitter/X',
                                                'instagram' => 'Instagram',
                                                'youtube' => 'YouTube',
                                                'website' => 'Site Pessoal',
                                                'other' => 'Outro',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL')
                                            ->url()
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null),
                            ]),
                        Tabs\Tab::make('Mídia')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->label('Foto de Perfil')
                                    ->collection('profile_photo')
                                    ->avatar()
                                    ->image()
                                    ->imageEditor(),
                                SpatieMediaLibraryFileUpload::make('cover_photo')
                                    ->label('Foto de Capa (Banner)')
                                    ->collection('cover_photo')
                                    ->image()
                                    ->imageEditor()
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Perfis')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
