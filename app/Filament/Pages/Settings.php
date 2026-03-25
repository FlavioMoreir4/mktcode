<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Settings extends SettingsPage
{
    protected static string $settings = GeneralSettings::class;

    protected static UnitEnum|string|null $navigationGroup = 'Configurações';

    protected static ?string $title = 'Geral';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::OutlinedCog6Tooth;

    public function form(Schema $schema): Schema
    {
        return $this->defaultForm($schema)
            ->components([
                Section::make('Informações do Site')
                    ->columns(2)
                    ->description('Dados principais que aparecem em todo o site')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nome do Site')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('MC - Marketing & Code'),

                        TextInput::make('contact_email')
                            ->label('Email de Contato')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('site_author')
                            ->label('Autor / Empresa')
                            ->maxLength(255)
                            ->placeholder('MC - Marketing & Code')
                            ->helperText('Usado em meta tags de autor e JSON-LD'),

                        Select::make('site_locale')
                            ->label('Idioma Padrão')
                            ->options([
                                'pt_BR' => 'Português (Brasil)',
                                'en_US' => 'English (US)',
                                'es_ES' => 'Español',
                            ])
                            ->required()
                            ->helperText('Define o locale para SEO e JSON-LD'),

                        Textarea::make('site_description')
                            ->label('Descrição do Site')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Aparece em meta tags e resumos'),

                        TextInput::make('site_keywords')
                            ->label('Palavras-chave (SEO)')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Separadas por vírgula. Ex: Laravel, PHP, desenvolvimento'),
                    ]),

                Section::make('Open Graph (Redes Sociais)')
                    ->columns(1)
                    ->description('Customiza como o site aparece ao compartilhar')
                    ->schema([
                        FileUpload::make('og_image')
                            ->label('Imagem Open Graph')
                            ->image()
                            ->maxSize(5120)
                            ->directory('settings/og')
                            ->disk('public')
                            ->helperText('Recomendado: 1200x630px, formato PNG ou JPG'),
                    ]),

                Section::make('Links Sociais')
                    ->columns(1)
                    ->description('URLs dos seus perfis nas redes')
                    ->schema([
                        KeyValue::make('social_links')
                            ->label('Redes Sociais')
                            ->keyLabel('Rede')
                            ->valueLabel('URL')
                            ->default([
                                'github' => '',
                                'linkedin' => '',
                                'twitter' => '',
                                'instagram' => '',
                            ])
                            ->helperText('Deixe em branco para não exibir'),
                    ]),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['social_links']) && is_array($data['social_links'])) {
            $data['social_links'] = array_filter(
                $data['social_links'],
                fn ($value) => ! blank($value)
            );
        }

        if (isset($data['site_keywords'])) {
            $data['site_keywords'] = mb_trim($data['site_keywords']);
        }

        if (isset($data['site_author'])) {
            $data['site_author'] = mb_trim($data['site_author']);
        }

        return $data;
    }

    public function getSavedNotificationTitle(): ?string
    {
        return '✓ Configurações salvas com sucesso!';
    }
}
