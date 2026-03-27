<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\SocialPlatform;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Usuário')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Perfil Público')
                            ->icon('heroicon-o-user-circle')
                            ->badge('?')
                            ->badgeColor('info')
                            ->badgeTooltip('Essas informações aparecem publicamente no seu perfil.')
                            ->schema([
                                Grid::make(1)->schema([
                                    TextInput::make('username')
                                        ->label('Username')
                                        ->prefix('mktcode.digital/u/')
                                        ->helperText('Seu identificador único na plataforma. Use apenas letras, números e hífens.')
                                        ->unique(ignoreRecord: true)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($set, $state) => $set('username', Str::slug($state)))
                                        ->required(),

                                    TextInput::make('title')
                                        ->label('Título profissional')
                                        ->placeholder('Full Stack Developer, UI Designer, etc')
                                        ->helperText('Ex: Desenvolvedor Front-end'),

                                    TextInput::make('location')
                                        ->label('Localização')
                                        ->placeholder('São Paulo, Brasil'),
                                ]),

                                RichEditor::make('bio')
                                    ->label('Biografia')

                                    ->toolbarButtons(['bold', 'italic', 'underline', 'link'])
                                    ->extraAttributes(['style' => 'min-height: 150px; max-height: 300px; overflow-y: auto;'])
                                    ->maxLength(500),
                            ]),

                        Tab::make('Redes sociais')
                            ->icon('heroicon-o-share')
                            ->badge('?')
                            ->badgeColor('info')
                            ->badgeTooltip('Adicione suas redes sociais e links públicos.')
                            ->schema([
                                Repeater::make('social_links')
                                    ->label('Links sociais')
                                    ->addActionLabel('Adicionar nova rede')
                                    ->schema([
                                        Select::make('platform')
                                            ->label('Plataforma')
                                            ->options(collect(SocialPlatform::cases())
                                                ->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->name)]))
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn ($get, $set, $state) => $set('url', $get('platform') ? SocialPlatform::from($state)->placeholder() : '')),

                                        TextInput::make('url')
                                            ->label('Link')

                                            ->url()
                                            ->required()
                                            ->rule(fn ($get) => $this->validateDomainRule($get('platform'))),
                                    ])
                                    ->columns(2)
                                    ->reorderable()
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Fotos do perfil')
                            ->icon('heroicon-o-camera')
                            ->badge('?')
                            ->badgeColor('info')
                            ->badgeTooltip('Adicione suas fotos de perfil e capa.')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->label('Avatar')
                                    ->collection('profile_photo')
                                    ->avatar()
                                    ->image()
                                    ->imageEditor()
                                    ->helperText('Formatos: JPG, PNG, GIF. Tamanho máximo: 2MB. Dimensão recomendada: 200x200px.')
                                    ->imagePreviewHeight('150px')
                                    ->columnSpanFull(),

                                SpatieMediaLibraryFileUpload::make('cover_photo')
                                    ->label('Imagem de capa')
                                    ->collection('cover_photo')
                                    ->image()
                                    ->imageEditor()
                                    ->helperText('Formatos: JPG, PNG. Tamanho máximo: 5MB. Dimensão recomendada: 1200x400px.')
                                    ->imagePreviewHeight('200px')
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ]),
                Section::make('Segurança da conta')
                    ->icon('heroicon-o-lock-closed')
                    ->description('Altere sua senha e configurações de segurança.')
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),
            ]);
    }

    private function validateDomainRule(?string $platform): ?callable
    {
        if (! $platform) {
            return null;
        }

        return function ($attribute, $value, $fail) use ($platform) {
            $expectedDomain = match ($platform) {
                'instagram' => 'instagram.com',
                'twitter' => 'x.com',
                'linkedin' => 'linkedin.com',
                'github' => 'github.com',
                default => null,
            };

            if ($expectedDomain && ! str_contains($value, $expectedDomain)) {
                $fail("O link deve pertencer ao domínio {$expectedDomain}.");
            }
        };
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
