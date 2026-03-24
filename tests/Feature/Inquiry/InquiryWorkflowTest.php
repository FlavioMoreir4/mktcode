<?php

declare(strict_types=1);

use App\Application\Inquiry\Commands\MarkInquiryInProgress;
use App\Application\Inquiry\Commands\ResolveInquiry;
use App\Application\Inquiry\Queries\GetInquiryActivityChartQuery;
use App\Application\Inquiry\Queries\GetInquiryMetricsQuery;
use App\Application\Inquiry\Queries\ListAdminInquiriesQuery;
use App\Application\Inquiry\Support\InquiryStatusView;
use App\Domain\Inquiry\Enums\InquiryStatus;
use App\Models\Inquiry;
use Carbon\CarbonImmutable;

it('transitions inquiries through the explicit workflow commands', function () {
    $inquiry = Inquiry::query()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Need help with a project.',
        'status' => InquiryStatus::New,
    ]);

    app(MarkInquiryInProgress::class)->handle($inquiry);
    expect($inquiry->fresh()->status)->toBe(InquiryStatus::InProgress);

    app(ResolveInquiry::class)->handle($inquiry->fresh());
    expect($inquiry->fresh()->status)->toBe(InquiryStatus::Resolved);
});

it('builds inquiry metrics in a sqlite-compatible way', function () {
    CarbonImmutable::setTestNow(now());

    $newInquiry = Inquiry::query()->create([
        'name' => 'New Lead',
        'email' => 'new@example.com',
        'message' => 'Fresh inquiry',
        'status' => InquiryStatus::New,
    ]);

    $newInquiry->forceFill([
        'created_at' => now()->subHours(30),
        'updated_at' => now()->subHours(30),
    ])->saveQuietly();

    $resolvedInquiry = Inquiry::query()->create([
        'name' => 'Resolved Lead',
        'email' => 'resolved@example.com',
        'message' => 'Resolved inquiry',
        'status' => InquiryStatus::Resolved,
    ]);

    $resolvedInquiry->forceFill([
        'created_at' => now()->subHours(10),
        'updated_at' => now()->subHours(2),
    ])->saveQuietly();

    $metrics = app(GetInquiryMetricsQuery::class)->summary();

    expect($metrics->pending)->toBe(1);
    expect($metrics->resolved)->toBe(1);
    expect($metrics->late)->toBe(1);
    expect($metrics->resolvedWithinSla)->toBe(1);
    expect($metrics->slaRate)->toBe(100);
});

it('builds inquiry activity chart data from the repository', function () {
    $chartInquiry = Inquiry::query()->create([
        'name' => 'Chart Lead',
        'email' => 'chart@example.com',
        'message' => 'Chart inquiry',
        'status' => InquiryStatus::New,
    ]);

    $chartInquiry->forceFill([
        'created_at' => now()->subDay(),
        'updated_at' => now()->subDay(),
    ])->saveQuietly();

    $chart = app(GetInquiryActivityChartQuery::class)->forDays(3);

    expect($chart['labels'])->toHaveCount(3);
    expect($chart['values'])->toHaveCount(3);
    expect(collect($chart['values'])->sum())->toBeGreaterThanOrEqual(1);
});

it('applies prioritized ordering for admin inquiries outside the model and resource', function () {
    Inquiry::query()->create([
        'name' => 'Resolved first',
        'email' => 'resolved@example.com',
        'message' => 'Resolved inquiry',
        'status' => InquiryStatus::Resolved,
    ]);

    Inquiry::query()->create([
        'name' => 'New priority',
        'email' => 'new@example.com',
        'message' => 'New inquiry',
        'status' => InquiryStatus::New,
    ]);

    $ordered = app(ListAdminInquiriesQuery::class)
        ->apply(Inquiry::query())
        ->latest()
        ->pluck('status')
        ->all();

    expect($ordered[0])->toBe(InquiryStatus::New);
});

it('centralizes inquiry admin status presentation semantics', function () {
    $newStatus = InquiryStatusView::from(InquiryStatus::New);
    $resolvedStatus = InquiryStatusView::from(InquiryStatus::Resolved);

    expect($newStatus->label)->toBe(InquiryStatus::New->getLabel() ?? InquiryStatus::New->value);
    expect($newStatus->color)->toBe((string) (InquiryStatus::New->getColor() ?? 'gray'));
    expect($newStatus->rowClass)->toBe('bg-gray-50 dark:bg-gray-800/40');
    expect($resolvedStatus->rowClass)->toBeNull();
});
