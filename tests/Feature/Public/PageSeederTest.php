<?php

declare(strict_types=1);

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Support\Facades\Artisan;

test('page seeder creates the privacy policy page with rendered html', function () {
    Artisan::call('db:seed', ['--class' => PageSeeder::class, '--force' => true]);

    $page = Page::query()->where('slug', 'politica-de-privacidade')->firstOrFail();

    expect($page->status)->toBe(App\Domain\Content\Enums\PageStatus::Published);
    expect($page->title)->toBe('Política de Privacidade');
    expect($page->html)->toContain('LGPD');
    expect($page->html)->toContain('Google');
    expect($page->html)->toContain('Meta');
    expect($page->html)->toContain('tracking');
    expect($page->html)->toContain('<h2');
    expect($page->html)->toContain('<ul');

    // Visível publicamente pela rota
    $this->get(route('public.page.show', $page->slug))
        ->assertSuccessful()
        ->assertInertia(fn (Inertia\Testing\AssertableInertia $page) => $page
            ->component('public/PageShow')
            ->where('page.slug', 'politica-de-privacidade'));
});

test('page seeder creates the terms of service page', function () {
    Artisan::call('db:seed', ['--class' => PageSeeder::class, '--force' => true]);

    $page = Page::query()->where('slug', 'termos-de-servico')->firstOrFail();

    expect($page->status)->toBe(App\Domain\Content\Enums\PageStatus::Published);
    expect($page->title)->toBe('Termos de Serviço');
    expect($page->html)->toContain('Google');
    expect($page->html)->toContain('Meta');
    expect($page->html)->toContain('intelectual');

    $this->get(route('public.page.show', $page->slug))
        ->assertSuccessful()
        ->assertInertia(fn (Inertia\Testing\AssertableInertia $page) => $page
            ->component('public/PageShow')
            ->where('page.slug', 'termos-de-servico'));
});

test('page seeder creates the cookie policy page', function () {
    Artisan::call('db:seed', ['--class' => PageSeeder::class, '--force' => true]);

    $page = Page::query()->where('slug', 'politica-de-cookies')->firstOrFail();

    expect($page->status)->toBe(App\Domain\Content\Enums\PageStatus::Published);
    expect($page->title)->toBe('Política de Cookies');
    expect($page->html)->toContain('cookies');
    expect($page->html)->toContain('Google');
    expect($page->html)->toContain('Meta');

    $this->get(route('public.page.show', $page->slug))
        ->assertSuccessful()
        ->assertInertia(fn (Inertia\Testing\AssertableInertia $page) => $page
            ->component('public/PageShow')
            ->where('page.slug', 'politica-de-cookies'));
});

test('legal pages are exposed as shared inertia prop', function () {
    Artisan::call('db:seed', ['--class' => PageSeeder::class, '--force' => true]);

    $this->get(route('home'))
        ->assertInertia(fn (Inertia\Testing\AssertableInertia $page) => $page
            ->has('legalPages', 3)
            ->where('legalPages.0.slug', 'politica-de-cookies')
            ->where('legalPages.1.slug', 'politica-de-privacidade')
            ->where('legalPages.2.slug', 'termos-de-servico'));
});
