<?php

declare(strict_types=1);

namespace App\Filament\Resources\Concerns;

use Awcodes\RicherEditor\Blocks\HighlightedCodeBlock;
use Awcodes\RicherEditor\Plugins\CodeBlockShikiPlugin;
use Awcodes\RicherEditor\Plugins\DebugPlugin;
use Awcodes\RicherEditor\Plugins\EmbedPlugin;
use Awcodes\RicherEditor\Plugins\EmojiPlugin;
use Awcodes\RicherEditor\Plugins\FakerPlugin;
use Awcodes\RicherEditor\Plugins\FigurePlugin;
use Awcodes\RicherEditor\Plugins\FullScreenPlugin;
use Awcodes\RicherEditor\Plugins\IdPlugin;
use Awcodes\RicherEditor\Plugins\LinkPlugin;
use Awcodes\RicherEditor\Plugins\PhikiCodeBlockPlugin;
use Awcodes\RicherEditor\Plugins\SourceCodePlugin;
use Awcodes\RicherEditor\Plugins\VideoPlugin;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\ToolbarButtonGroup;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

trait HasDynamicRichEditor
{
    /**
     * Plugins base reutilizáveis
     */
    protected static function basePlugins(): array
    {
        $plugins = [
            EmbedPlugin::make(),
            EmojiPlugin::make(),
            FullScreenPlugin::make(),
            IdPlugin::make(),
            LinkPlugin::make(),
            SourceCodePlugin::make(),
            VideoPlugin::make(),
            FigurePlugin::make(),
            CodeBlockShikiPlugin::make(),
            PhikiCodeBlockPlugin::make(),
        ];

        if (app()->environment('local')) {
            $plugins[] = DebugPlugin::make();
            $plugins[] = FakerPlugin::make();
        }

        return $plugins;
    }

    /**
     * Toolbar completa estilo CMS profissional
     */
    protected static function fullToolbar(): array
    {
        return [
            [ToolbarButtonGroup::make('Parágrafo & Títulos', [
                'paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            ])],

            [
                'bold', 'italic', 'underline', 'strike',
                'subscript', 'superscript',
                'alignStart', 'alignCenter', 'alignEnd', 'alignJustify',
            ],

            [
                'highlight',
                'textColor',
                'link',
                'embed',
                'video',
            ],

            [
                'blockquote',
                'code',
                'codeBlock',
                'bulletList',
                'orderedList',
                'horizontalRule',
            ],

            [
                'table',
                'attachFiles',
                'grid',
                'customBlocks',
                'mergeTags',
            ],

            [
                'undo',
                'redo',
                'clearFormatting',
                'sourceCode',
                'fullscreen',
            ],
        ];
    }

    /**
     * Editor FULL profissional
     *
     * @mixin \Filament\Forms\Components\RichEditor
     *
     * @method static self maxHeight(string $value)
     */
    public static function getFullRichEditor(string $column = 'body'): RichEditor
    {
        return RichEditor::make($column)
            ->label('Conteúdo completo')

            ->json()

            ->placeholder('Digite o conteúdo completo. Suporta vídeo, código, tabelas, imagens e embeds.')

            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('editor')

            ->maxHeight('800px')

            ->resizableImages()
            ->customTextColors()

            ->mergeTags([
                'nome' => 'Nome do cliente',
                'email' => 'E-mail',
                'telefone' => 'Telefone',
                'data' => 'Data atual',
            ])

            ->plugins(self::basePlugins())

            ->customBlocks([
                HighlightedCodeBlock::class,
            ])

            ->toolbarButtons(self::fullToolbar());
    }

    /**
     * Editor estilo Notion (baseado em blocos)
     *
     * @mixin \Filament\Forms\Components\RichEditor
     *
     * @method static self maxHeight(string $value)
     */
    public static function getNotionRichEditor(string $column = 'body'): RichEditor
    {
        return RichEditor::make($column)
            ->label('Conteúdo (modo Notion)')

            ->json()

            ->placeholder('Digite / para inserir blocos...')

            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('notion')

            ->maxHeight('900px')

            ->plugins(self::basePlugins())

            ->customBlocks([
                HighlightedCodeBlock::class,
            ])

            ->toolbarButtons([
                ['paragraph', 'h1', 'h2', 'h3'],
                ['bold', 'italic', 'underline'],
                ['bulletList', 'orderedList', 'blockquote'],
                ['table', 'grid', 'embed', 'video'],
                ['attachFiles', 'customBlocks'],
                ['undo', 'redo', 'fullscreen'],
            ]);
    }

    /**
     * Editor Markdown + RichEditor juntos
     */
    public static function getMarkdownRichEditor(string $column = 'body'): Tabs
    {
        return Tabs::make('Editor')
            ->tabs([
                Tab::make('Rich Editor')
                    ->schema([
                        self::getFullRichEditor($column),
                    ]),

                Tab::make('Markdown')
                    ->schema([
                        Textarea::make($column.'_markdown')
                            ->label('Markdown')
                            ->rows(20)
                            ->placeholder("# Título\n\nDigite em Markdown aqui..."),
                    ]),
            ]);
    }

    /**
     * Editor simples
     *
     * @mixin \Filament\Forms\Components\RichEditor
     *
     * @method static self maxHeight(string $value)
     */
    public static function getSimpleRichEditor(string $column = 'description'): RichEditor
    {
        return RichEditor::make($column)
            ->label('Descrição')
            ->maxHeight('300px')
            ->plugins([SourceCodePlugin::make()])
            ->toolbarButtons([
                ['bold', 'italic', 'underline'],
                ['bulletList', 'orderedList'],
                ['link'],
                ['undo', 'redo'],
            ]);
    }

    /**
     * Editor para Blog
     *
     * @mixin \Filament\Forms\Components\RichEditor
     *
     * @method static self maxHeight(string $value)
     */
    public static function getBlogRichEditor(string $column = 'body'): RichEditor
    {
        return RichEditor::make($column)
            ->label('Corpo do Artigo')

            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('blog')

            ->maxHeight('800px')

            ->plugins(self::basePlugins())

            ->customBlocks([
                HighlightedCodeBlock::class,
            ])

            ->toolbarButtons([
                ['h1', 'h2', 'h3', 'h4'],
                ['bold', 'italic', 'underline', 'strike'],
                ['bulletList', 'orderedList', 'blockquote'],
                ['table', 'embed', 'video'],
                ['codeBlock', 'sourceCode'],
                ['undo', 'redo', 'fullscreen'],
            ]);
    }
}
