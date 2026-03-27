<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Shared\Enums\SocialPlatform;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Usuário')
                ->columnSpanFull()
                ->tabs([
                    $this->getPublicProfileTab(),
                    $this->getSocialLinksTab(),
                    $this->getPhotosTab(),
                    $this->getSecurityTab(),
                ]),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    /** ------------------ Abas ------------------ */
    private function getPublicProfileTab(): Tab
    {
        return Tab::make('Perfil Público')
            ->icon('heroicon-o-user-circle')
            ->badge('?')
            ->badgeColor('info')
            ->badgeTooltip('Essas informações aparecem publicamente no seu perfil.')
            ->schema([
                Grid::make(1)->schema([
                    $this->getUsernameFormComponent(),
                    $this->getTitleFormComponent(),
                    $this->getLocationFormComponent(),
                    $this->getSkillsFormComponent(),
                ]),
                $this->getBioFormComponent(),
            ]);
    }

    private function getSkillsFormComponent(): \Filament\Forms\Components\TagsInput
    {
        return \Filament\Forms\Components\TagsInput::make('skills')
            ->label('Stacks / Skills')
            ->placeholder('Digite e pressione Enter')
            ->helperText('Adicione suas principais tecnologias, ex: Laravel, Vue, Docker')
            ->separator(',')
            ->splitKeys(['Tab', 'Enter', ','])
            ->reorderable();
    }

    private function getSocialLinksTab(): Tab
    {
        return Tab::make('Redes sociais')
            ->icon('heroicon-o-share')
            ->badge('?')
            ->badgeColor('info')
            ->badgeTooltip('Adicione suas redes sociais e links públicos.')
            ->schema([
                $this->getSocialLinksRepeater(),
            ]);
    }

    private function getPhotosTab(): Tab
    {
        return Tab::make('Fotos do perfil')
            ->icon('heroicon-o-camera')
            ->badge('?')
            ->badgeColor('info')
            ->badgeTooltip('Adicione suas fotos de perfil e capa.')
            ->schema([
                $this->getProfilePhotoUpload(),
                $this->getCoverPhotoUpload(),
            ])->columns(2);
    }

    private function getSecurityTab(): Tab
    {
        return Tab::make('Segurança da conta')
            ->icon('heroicon-o-lock-closed')
            ->badge('?')
            ->badgeColor('info')
            ->badgeTooltip('Altere sua senha e configurações de segurança.')
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                ...\Illuminate\Support\Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
            ]);
    }

    /** ------------------ Componentes ------------------ */
    private function getUsernameFormComponent(): TextInput
    {
        return TextInput::make('username')
            ->label('Username')
            ->prefix('mktcode.digital/u/')
            ->helperText('Seu identificador único na plataforma. Use apenas letras, números e hífens.')
            ->unique(ignoreRecord: true)
            ->live(onBlur: true)
            ->afterStateUpdated(fn ($set, $state) => $set('username', Str::slug($state)))
            ->required();
    }

    private function getTitleFormComponent(): TextInput
    {
        return TextInput::make('title')
            ->label('Título profissional')
            ->placeholder('Full Stack Developer, UI Designer, etc')
            ->helperText('Ex: Desenvolvedor Front-end');
    }

    private function getLocationFormComponent(): TextInput
    {
        return TextInput::make('location')
            ->label('Localização')
            ->placeholder('São Paulo, Brasil');
    }

    private function getBioFormComponent(): RichEditor
    {
        return RichEditor::make('bio')
            ->label('Biografia')
            ->toolbarButtons(['bold', 'italic', 'underline', 'link'])
            ->extraAttributes(['style' => 'min-height: 150px; max-height: 300px; overflow-y: auto;'])
            ->maxLength(500);
    }

    private function getSocialLinksRepeater(): Repeater
    {
        return Repeater::make('social_links')
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
                    ->afterStateUpdated(
                        fn ($get, $set, $state) => $set('url', $state ? SocialPlatform::from($state)->placeholder() : '')
                    ),

                TextInput::make('url')
                    ->label('Link')
                    ->url()
                    ->required()
                    ->rule(function ($get) {
                        $platform = $get('platform');
                        if (! $platform) {
                            return null;
                        }

                        $enum = SocialPlatform::from($platform);
                        $expectedDomain = $enum->domain();

                        return function ($attribute, $value, $fail) use ($expectedDomain) {
                            if ($expectedDomain && ! str_contains($value, $expectedDomain)) {
                                $fail("O link deve pertencer ao domínio {$expectedDomain}.");
                            }
                        };
                    }),

                Grid::make(3)->columnSpanFull()->schema([
                    Toggle::make('featured')
                        ->label('Destacar')
                        ->helperText('Exibe como botão de ação no topo do perfil.')
                        ->default(false),

                    Toggle::make('icon_only')
                        ->label('Só ícone')
                        ->helperText('Oculta o rótulo de texto no pill.')
                        ->default(false),

                    Toggle::make('stack_on_mobile')
                        ->label('Empilhar no mobile')
                        ->helperText('Ocupa a linha inteira em telas pequenas.')
                        ->default(false),
                ]),
            ])
            ->columns(2)
            ->reorderable()
            ->columnSpanFull();
    }

    private function getProfilePhotoUpload(): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make('profile_photo')
            ->label('Avatar')
            ->collection('profile_photo')
            ->avatar()
            ->image()
            ->imageEditor()
            ->helperText('Formatos: JPG, PNG, GIF. Tamanho máximo: 2MB. Dimensão recomendada: 200x200px.')
            ->imagePreviewHeight('150px')
            ->columnSpanFull();
    }

    private function getCoverPhotoUpload(): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make('cover_photo')
            ->label('Imagem de capa')
            ->collection('cover_photo')
            ->image()
            ->imageEditor()
            ->helperText('Formatos: JPG, PNG. Tamanho máximo: 5MB. Dimensão recomendada: 1200x400px.')
            ->imagePreviewHeight('200px')
            ->columnSpanFull();
    }

    /** ------------------ Configuração ------------------ */
    public function getMaxContentWidth(): Width
    {
        return Width::SevenExtraLarge;
    }

    public static function isSimple(): bool
    {
        return false;
    }
}
