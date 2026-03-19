<?php

declare(strict_types=1);

namespace App\Helpers;

use Filament\Forms\Components\RichEditor\RichContentRenderer;

class ContentRenderer
{
    public static function toHtml(string $content): string
    {
        return RichContentRenderer::make($content)
            ->linkHeadings(level: 2, wrap: false) // transforma H2+ em links
            ->toHtml();
    }

    public static function toMarkdown(string $content): string
    {
        return RichContentRenderer::make($content)
            ->toMarkdown();
    }

    public static function tableOfContents(string $content): string
    {
        return \Awcodes\RicherEditor\Support\TableOfContents::make($content)
            ->asHtml();
    }
}
