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

    abstract protected function getRichContentField(): string; // Example: 'body' or 'content'

    protected static function bootHasRichEditorRendering(): void
    {
        static::saving(function ($model) {
            $field = method_exists($model, 'getRichContentField') ? $model->getRichContentField() : 'body';

            // Only update if the base editor content field is dirty
            if ($model->isDirty($field)) {
                $model->html = $model->parseHtml();
                $model->plain_text = $model->parsePlainText();
                $model->word_count = $model->parseWordCount();
                $model->reading_time = $model->parseReadingTime();
            }
        });
    }

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
        if (array_key_exists('html', $this->attributes) && $this->attributes['html'] !== null) {
            return $this->attributes['html'];
        }

        return $this->parseHtml();
    }

    public function parseHtml(): string
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
                // ->linkHeadings(level: 2, wrap: true)
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
        return $this->parseMarkdown();
    }

    public function parseMarkdown(): string
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
        if (array_key_exists('plain_text', $this->attributes) && $this->attributes['plain_text'] !== null) {
            return $this->attributes['plain_text'];
        }

        return $this->parsePlainText();
    }

    public function parsePlainText(): string
    {
        return Str::of($this->parseHtml())
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
        if (array_key_exists('word_count', $this->attributes) && $this->attributes['word_count'] !== null) {
            return (int) $this->attributes['word_count'];
        }

        return $this->parseWordCount();
    }

    public function parseWordCount(): int
    {
        return str_word_count($this->parsePlainText());
    }

    public function getReadingTimeAttribute(): int
    {
        if (array_key_exists('reading_time', $this->attributes) && $this->attributes['reading_time'] !== null) {
            return (int) $this->attributes['reading_time'];
        }

        return $this->parseReadingTime();
    }

    public function parseReadingTime(): int
    {
        return max(1, (int) ceil($this->parseWordCount() / 200));
    }
}
