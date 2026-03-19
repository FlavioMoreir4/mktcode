<?php

declare(strict_types=1);

namespace App\Filament\Resources\Concerns;

use Awcodes\RicherEditor\Blocks\HighlightedCodeBlock;
use Awcodes\RicherEditor\Support\TableOfContents;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use Phiki\Theme\Theme;

trait HasRichEditorRendering
{
    /**
     * Detectar se o conteúdo é JSON do RichEditor
     */
    protected function isRichEditorContent(): bool
    {
        return is_array($this->body);
    }

    /**
     * Detectar se é Markdown puro
     */
    protected function isMarkdownContent(): bool
    {
        return is_string($this->body) && Str::contains($this->body, ['#', '**', '-', '*', '```']);
    }

    /**
     * Render HTML final (principal)
     */
    public function getHtmlAttribute(): string
    {

        if (! $this->body) {
            return '';
        }
        // Conteúdo vindo do RichEditor (JSON)
        if ($this->isRichEditorContent()) {
            return RichContentRenderer::make($this->body)
                ->customBlocks([
                    HighlightedCodeBlock::class => [
                        'dark' => Theme::GithubDark,
                        'light' => Theme::GithubLight,
                    ],
                ])
                ->linkHeadings(level: 2, wrap: false)
                ->toHtml();
        }

        // Conteúdo vindo de Markdown puro
        if ($this->isMarkdownContent()) {
            $converter = new CommonMarkConverter;

            return $converter->convert($this->body)->getContent();
        }

        return (string) $this->body;
    }

    /**
     * Retornar como Markdown (mesmo se vier do RichEditor)
     */
    public function getMarkdownAttribute(): string
    {
        if (! $this->body) {
            return '';
        }

        if ($this->isRichEditorContent()) {
            return RichContentRenderer::make($this->body)->toMarkdown();
        }

        return (string) $this->body;
    }

    /**
     * Conteúdo limpo para SEO (sem HTML pesado)
     */
    public function getPlainTextAttribute(): string
    {
        return Str::of($this->html)
            ->stripTags()
            ->replace('&nbsp;', ' ')
            ->squish()
            ->toString();
    }

    /**
     * Excerpt automático
     */
    // public function getExcerptAttribute(): string
    // {
    //     return Str::limit($this->plain_text, 160);
    // }

    /**
     * Table of Contents HTML
     */
    public function getTocAttribute(): string
    {
        if (! $this->isRichEditorContent()) {
            return '';
        }

        return TableOfContents::make($this->body)->asHtml();
    }

    /**
     * Table of Contents como array (bom para Vue/Livewire)
     */
    public function getTocArrayAttribute(): array
    {
        if (! $this->isRichEditorContent()) {
            return [];
        }

        return TableOfContents::make($this->body)->asArray();
    }

    /**
     * Contagem de palavras (útil para blog)
     */
    public function getWordCountAttribute(): int
    {
        return str_word_count($this->plain_text);
    }

    /**
     * Tempo estimado de leitura
     */
    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil($this->word_count / 200));
    }
}
