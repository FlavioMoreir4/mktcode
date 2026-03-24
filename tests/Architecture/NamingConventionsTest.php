<?php

declare(strict_types=1);

function phpFilesIn(string $path): array
{
    if (! is_dir($path)) {
        return [];
    }

    return collect(iterator_to_array(
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        )
    ))
        ->filter(fn (SplFileInfo $file): bool => $file->isFile() && $file->getExtension() === 'php')
        ->map(fn (SplFileInfo $file): string => $file->getPathname())
        ->values()
        ->all();
}

test('application query classes use the query suffix', function () {
    $queryFiles = phpFilesIn(dirname(__DIR__, 2).'/app/Application');

    $invalid = collect($queryFiles)
        ->filter(fn (string $path): bool => str_contains($path, '/Queries/'))
        ->reject(fn (string $path): bool => str_ends_with($path, 'Query.php'))
        ->values()
        ->all();

    expect($invalid)->toBe([]);
});

test('application command classes use an imperative use-case name', function () {
    $commandFiles = phpFilesIn(dirname(__DIR__, 2).'/app/Application');

    $invalid = collect($commandFiles)
        ->filter(fn (string $path): bool => str_contains($path, '/Commands/'))
        ->reject(function (string $path): bool {
            $filename = basename($path, '.php');

            return preg_match('/^(Submit|Mark|Resolve|Publish|Schedule|Unpublish|Assign|Reorder|Feature|Update)/', $filename) === 1
                || str_ends_with($filename, 'Command');
        })
        ->values()
        ->all();

    expect($invalid)->toBe([]);
});

test('infrastructure listeners keep an explicit listener or notification-oriented name', function () {
    $listenerFiles = phpFilesIn(dirname(__DIR__, 2).'/app/Infrastructure');

    $invalid = collect($listenerFiles)
        ->filter(fn (string $path): bool => str_contains($path, '/Listeners/'))
        ->reject(function (string $path): bool {
            $filename = basename($path, '.php');

            return str_ends_with($filename, 'Listener')
                || str_ends_with($filename, 'Notification');
        })
        ->values()
        ->all();

    expect($invalid)->toBe([]);
});

test('application services keep an explicit workflow or service-oriented name', function () {
    $serviceFiles = phpFilesIn(dirname(__DIR__, 2).'/app/Application');

    $invalid = collect($serviceFiles)
        ->filter(fn (string $path): bool => str_contains($path, '/Services/'))
        ->reject(function (string $path): bool {
            $filename = basename($path, '.php');

            return str_ends_with($filename, 'Workflow')
                || str_ends_with($filename, 'Service')
                || str_ends_with($filename, 'Decider');
        })
        ->values()
        ->all();

    expect($invalid)->toBe([]);
});
