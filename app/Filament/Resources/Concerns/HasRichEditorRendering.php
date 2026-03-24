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
    abstract protected function getEditorContent(): string|array|null;

    protected function isRichEditorContent(): bool
    {
        return is_array($this->getEditorContent());
    }

    protected function isMarkdownContent(): bool
    {
        return is_string($this->getEditorContent()) && Str::contains($this->getEditorContent(), ['#', '**', '-', '*', '```']);
    }

    public function getHtmlAttribute(): string
    {
        if (! $this->getEditorContent()) {
            return '';
        }

        if ($this->isRichEditorContent()) {
            return RichContentRenderer::make($this->getEditorContent())
                ->customBlocks([
                    HighlightedCodeBlock::class => [
                        'dark' => Theme::GithubDark,
                        'light' => Theme::GithubLight,
                    ],
                ])
                ->linkHeadings(level: 2, wrap: false)
                ->toHtml();
        }

        if ($this->isMarkdownContent()) {
            $converter = new CommonMarkConverter;

            return $converter->convert($this->getEditorContent())->getContent();
        }

        return (string) $this->getEditorContent();
    }

    public function getMarkdownAttribute(): string
    {
        if (! $this->getEditorContent()) {
            return '';
        }

        if ($this->isRichEditorContent()) {
            return RichContentRenderer::make($this->getEditorContent())->toMarkdown();
        }

        return (string) $this->getEditorContent();
    }

    public function getPlainTextAttribute(): string
    {
        return Str::of($this->html)
            ->stripTags()
            ->replace('&nbsp;', ' ')
            ->squish()
            ->toString();
    }

    public function getTocAttribute(): string
    {
        if (! $this->isRichEditorContent()) {
            return '';
        }

        return TableOfContents::make($this->getEditorContent())->asHtml();
    }

    public function getTocArrayAttribute(): array
    {
        if (! $this->isRichEditorContent()) {
            return [];
        }

        return TableOfContents::make($this->getEditorContent())->asArray();
    }

    public function getWordCountAttribute(): int
    {
        return str_word_count($this->plain_text);
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil($this->word_count / 200));
    }
}
