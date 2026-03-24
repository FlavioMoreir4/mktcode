<?php

declare(strict_types=1);

function adapterContents(array $paths): string
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

test('consolidated adapters avoid service locator usage', function () {
    $contents = adapterContents([
        dirname(__DIR__, 2).'/app/Filament/Widgets',
        dirname(__DIR__, 2).'/app/Filament/Resources/InquiryResource.php',
        dirname(__DIR__, 2).'/app/Filament/Resources/PostResource.php',
        dirname(__DIR__, 2).'/app/Filament/Resources/PostResource',
        dirname(__DIR__, 2).'/app/Filament/Resources/ProjectResource.php',
        dirname(__DIR__, 2).'/app/Filament/Resources/ProjectResource',
        dirname(__DIR__, 2).'/app/Http/Middleware/HandleInertiaRequests.php',
    ]);

    expect($contents)->not->toContain('app(');
    expect($contents)->not->toContain('resolve(');
});

test('user model does not resolve admin access from the container directly', function () {
    $contents = file_get_contents(dirname(__DIR__, 2).'/app/Models/User.php');

    expect($contents)->not->toContain('app(AdminAccessDecider::class)');
});
