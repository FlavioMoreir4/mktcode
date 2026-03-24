<?php

declare(strict_types=1);

function readSpecificPhpContents(array $paths): string
{
    return collect($paths)
        ->flatMap(function (string $path): array {
            if (is_dir($path)) {
                return collect(iterator_to_array(
                    new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path)
                    )
                ))
                    ->filter(fn (SplFileInfo $file): bool => $file->isFile() && $file->getExtension() === 'php')
                    ->map(fn (SplFileInfo $file): string => file_get_contents($file->getPathname()) ?: '')
                    ->all();
            }

            if (is_file($path)) {
                return [file_get_contents($path) ?: ''];
            }

            return [];
        })
        ->implode("\n");
}

test('content and portfolio admin adapters do not depend on legacy application or enum namespaces', function () {
    $contents = readSpecificPhpContents([
        dirname(__DIR__, 2).'/app/Application/Content',
        dirname(__DIR__, 2).'/app/Application/Portfolio',
        dirname(__DIR__, 2).'/app/Filament/Resources/PostResource.php',
        dirname(__DIR__, 2).'/app/Filament/Resources/PostResource',
        dirname(__DIR__, 2).'/app/Filament/Resources/ProjectResource.php',
        dirname(__DIR__, 2).'/app/Filament/Resources/ProjectResource',
    ]);

    expect($contents)->not->toContain('App\\Enums\\');
    expect($contents)->not->toContain('App\\Application\\Inquiries\\');
});
