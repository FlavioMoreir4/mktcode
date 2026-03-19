<?php

declare(strict_types=1);

namespace App\Filament\Resources\Concerns;

trait HasRichEditorFaker
{
    /**
     * Gerar conteúdo fake para testes/desenvolvimento
     */
    public static function generateFakeContent(
        int $headings = 1,
        int $paragraphs = 3,
        int $lists = 1,
        bool $codeBlock = true,
    ): array {
        $faker = \Awcodes\RicherEditor\Support\RichContentFaker::make();

        for ($i = 0; $i < $headings; $i++) {
            $faker->heading(level: 2);
        }

        for ($i = 0; $i < $paragraphs; $i++) {
            $faker->paragraphs(
                count: 1,
                links: true,
                bold: true,
                italic: true,
                code: true,
            );
        }

        for ($i = 0; $i < $lists; $i++) {
            $faker->list(count: 5, ordered: $i % 2 === 0);
        }

        if ($codeBlock) {
            $faker->codeBlock(language: 'php');
        }

        $faker->blockquote();
        $faker->hr();

        return $faker->asJson();
    }
}
