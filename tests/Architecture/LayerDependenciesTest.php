<?php

declare(strict_types=1);

function readPhpContents(string $path): string
{
    if (! is_dir($path)) {
        return '';
    }

    return collect(iterator_to_array(
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        )
    ))
        ->filter(fn (SplFileInfo $file): bool => $file->isFile() && $file->getExtension() === 'php')
        ->map(fn (SplFileInfo $file): string => file_get_contents($file->getPathname()) ?: '')
        ->implode("\n");
}

/**
 * @return list<string>
 */
function phpFilePaths(string $path): array
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

test('domain layer stays free from framework adapters and service location', function () {
    $contents = readPhpContents(dirname(__DIR__, 2).'/app/Domain');

    expect($contents)->not->toContain('Filament\\');
    expect($contents)->not->toContain('Inertia\\');
    expect($contents)->not->toContain('Illuminate\\Http');
    expect($contents)->not->toContain('JsonResource');
    expect($contents)->not->toContain('Notification');
    expect($contents)->not->toContain('Artisan');
    expect($contents)->not->toContain('DB::');
    expect($contents)->not->toContain('app(');
});

test('application layer stays free from filament and http adapters', function () {
    $contents = readPhpContents(dirname(__DIR__, 2).'/app/Application');

    expect($contents)->not->toContain('Filament\\');
    expect($contents)->not->toContain('App\\Http\\Controllers\\');
    expect($contents)->not->toContain('App\\Http\\Resources\\');
    expect($contents)->not->toContain('Illuminate\\Http');
});

test('public adapters do not rely on raw db expressions as business logic', function () {
    $contents = readPhpContents(dirname(__DIR__, 2).'/app/Http');

    expect($contents)->not->toContain('DB::');
    expect($contents)->not->toContain('selectRaw(');
    expect($contents)->not->toContain('whereRaw(');
});

test('administrative adapters avoid raw sorting expressions and keep inquiry ordering outside resources', function () {
    $contents = readPhpContents(dirname(__DIR__, 2).'/app/Filament');

    expect($contents)->not->toContain('orderByRaw(');
});

test('raw ordering expressions stay restricted to inquiry query and repository infrastructure', function () {
    $root = dirname(__DIR__, 2);
    $allowed = [
        realpath($root.'/app/Application/Inquiry/Queries/ListAdminInquiriesQuery.php'),
        realpath($root.'/app/Infrastructure/Inquiry/Persistence/Eloquent/EloquentInquiryRepository.php'),
    ];

    $invalid = collect([
        ...phpFilePaths($root.'/app/Application'),
        ...phpFilePaths($root.'/app/Http'),
        ...phpFilePaths($root.'/app/Filament'),
        ...phpFilePaths($root.'/app/Infrastructure'),
    ])
        ->filter(function (string $path) use ($allowed): bool {
            $contents = file_get_contents($path) ?: '';

            return str_contains($contents, 'orderByRaw(')
                && ! in_array(realpath($path), $allowed, true);
        })
        ->values()
        ->all();

    expect($invalid)->toBe([]);
});

test('consolidated models avoid administrative presentation helper methods', function () {
    $models = [
        dirname(__DIR__, 2).'/app/Models/Inquiry.php',
        dirname(__DIR__, 2).'/app/Models/Post.php',
        dirname(__DIR__, 2).'/app/Models/Project.php',
        dirname(__DIR__, 2).'/app/Models/User.php',
    ];

    foreach ($models as $path) {
        $contents = file_get_contents($path) ?: '';

        expect($contents)->not->toMatch('/function\s+get[A-Za-z0-9_]*Label\s*\(/');
        expect($contents)->not->toMatch('/function\s+get[A-Za-z0-9_]*Color\s*\(/');
        expect($contents)->not->toMatch('/function\s+[A-Za-z0-9_]*Badge[A-Za-z0-9_]*\s*\(/');
        expect($contents)->not->toMatch('/function\s+[A-Za-z0-9_]*Display[A-Za-z0-9_]*\s*\(/');
    }
});

test('inquiry administrative adapters use the shared inquiry status view', function () {
    $resource = file_get_contents(dirname(__DIR__, 2).'/app/Filament/Resources/InquiryResource.php') ?: '';
    $widget = file_get_contents(dirname(__DIR__, 2).'/app/Filament/Widgets/LatestInquiries.php') ?: '';

    expect($resource)->toContain('InquiryStatusView::from(');
    expect($widget)->toContain('InquiryStatusView::from(');
});
