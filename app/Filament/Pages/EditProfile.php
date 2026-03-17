<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações da Conta')
                    ->description('Atualize os dados básicos da sua conta.')
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),

                Section::make('Perfil Público')
                    ->description('Como você aparece para os outros usuários.')
                    ->schema([
                        TextInput::make('username')
                            ->label('Username')
                            ->prefix('mktcode.digital/u/')
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('username', Str::slug($state))),

                        TextInput::make('title')
                            ->label('Título Profissional'),

                        TextInput::make('location')
                            ->label('Localização'),

                        RichEditor::make('bio')
                            ->label('Biografia')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Redes Sociais')
                    ->schema([
                        Repeater::make('social_links')
                            ->label('Links')
                            ->schema([
                                Select::make('platform')
                                    ->options([
                                        'github' => 'GitHub',
                                        'linkedin' => 'LinkedIn',
                                        'twitter' => 'Twitter',
                                        'instagram' => 'Instagram',
                                        'website' => 'Website',
                                    ])
                                    ->required(),
                                TextInput::make('url')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Mídia')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('profile_photo')
                            ->label('Avatar / Foto de Perfil')
                            ->collection('profile_photo')
                            ->avatar()
                            ->image(),

                        SpatieMediaLibraryFileUpload::make('cover_photo')
                            ->label('Capa do Perfil')
                            ->collection('cover_photo')
                            ->image()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public function getMaxContentWidth(): Width
    {
        return Width::SevenExtraLarge;
    }

    public static function isSimple(): bool
    {
        return false;
    }
}
