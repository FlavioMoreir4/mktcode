<?php

declare(strict_types=1);

function legacyCleanupContents(array $paths): string
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

test('internal app code no longer depends on legacy bridge namespaces', function () {
    $contents = legacyCleanupContents([
        dirname(__DIR__, 2).'/app/Application/Content',
        dirname(__DIR__, 2).'/app/Application/Identity',
        dirname(__DIR__, 2).'/app/Application/Inquiry',
        dirname(__DIR__, 2).'/app/Application/Portfolio',
        dirname(__DIR__, 2).'/app/Actions',
        dirname(__DIR__, 2).'/app/Filament',
        dirname(__DIR__, 2).'/app/Http',
    ]);

    expect($contents)->not->toContain('App\\Enums\\');
    expect($contents)->not->toContain('App\\Application\\Inquiries\\');
    expect($contents)->not->toContain('App\\Listeners\\Inquiries\\');
});

test('feature tests no longer depend on legacy bridge namespaces', function () {
    $contents = legacyCleanupContents([
        dirname(__DIR__, 2).'/tests/Feature',
    ]);

    expect($contents)->not->toContain('App\\Enums\\');
    expect($contents)->not->toContain('App\\Application\\Inquiries\\');
    expect($contents)->not->toContain('App\\Listeners\\Inquiries\\');
});

test('seo legacy factory and contract are removed from internal code paths', function () {
    expect(is_file(dirname(__DIR__, 2).'/app/SEO/SeoFactory.php'))->toBeFalse();
    expect(is_file(dirname(__DIR__, 2).'/app/SEO/Contracts/HasSeo.php'))->toBeFalse();
});

test('legacy bridge files and directories are removed', function () {
    $root = dirname(__DIR__, 2);

    expect(is_file($root.'/app/Enums/InquiryStatus.php'))->toBeFalse();
    expect(is_file($root.'/app/Enums/PostStatus.php'))->toBeFalse();
    expect(is_file($root.'/app/Enums/ProjectStatus.php'))->toBeFalse();
    expect(is_file($root.'/app/Application/Inquiries/Actions/SubmitInquiry.php'))->toBeFalse();
    expect(is_file($root.'/app/Application/Inquiries/Queries/InquiryMetricsQuery.php'))->toBeFalse();
    expect(is_file($root.'/app/Application/Inquiries/Queries/InquiryActivityChartQuery.php'))->toBeFalse();
    expect(is_file($root.'/app/Listeners/Inquiries/SendInquirySubmittedNotification.php'))->toBeFalse();
    expect(is_dir($root.'/app/Application/Inquiries'))->toBeFalse();
    expect(is_dir($root.'/app/Listeners/Inquiries'))->toBeFalse();
});

test('user model no longer exposes legacy admin access helper', function () {
    $contents = file_get_contents(dirname(__DIR__, 2).'/app/Models/User.php');

    expect($contents)->not->toContain('public function canAccessAdminPanel(');
});

test('unused interface and legacy seo directories are removed', function () {
    $root = dirname(__DIR__, 2);

    expect(is_dir($root.'/app/Interfaces'))->toBeFalse();
    expect(is_dir($root.'/app/SEO'))->toBeFalse();
});

test('orphan process inquiry action and stats overview widget are removed', function () {
    $root = dirname(__DIR__, 2);

    expect(is_file($root.'/app/Actions/Public/ProcessInquiry.php'))->toBeFalse();
    expect(is_file($root.'/app/Filament/Widgets/StatsOverview.php'))->toBeFalse();
});

test('unused auxiliary widgets are removed from the consolidated admin dashboard', function () {
    $root = dirname(__DIR__, 2);

    expect(is_file($root.'/app/Filament/Widgets/InquiryPerformance.php'))->toBeFalse();
    expect(is_file($root.'/app/Filament/Widgets/LatestUsers.php'))->toBeFalse();
});

test('inquiry model no longer exposes admin presentation helpers or priority sorting scope', function () {
    $contents = file_get_contents(dirname(__DIR__, 2).'/app/Models/Inquiry.php');

    expect($contents)->not->toContain('getStatusLabel(');
    expect($contents)->not->toContain('getStatusColor(');
    expect($contents)->not->toContain('scopePrioritizeNew(');
});

test('public resources stay free from eloquent loading helpers', function () {
    $contents = legacyCleanupContents([
        dirname(__DIR__, 2).'/app/Http/Resources/Public',
    ]);

    expect($contents)->not->toContain('whenLoaded(');
});
