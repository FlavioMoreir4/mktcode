<?php

declare(strict_types=1);

use App\Application\Shared\DTOs\SeoData;
use App\Infrastructure\Shared\SEO\Builders\PageSeoBuilder;
use App\Infrastructure\Shared\SEO\SeoService;
use App\Settings\GeneralSettings;

beforeEach(function (): void {
    $settings = app(GeneralSettings::class);
    $settings->site_name = 'Test Site';
    $settings->site_description = 'Test description for SEO';
    $settings->site_keywords = 'laravel, php, teste';
    $settings->og_image = null;
    $settings->site_author = 'Test Author';
    $settings->site_locale = 'pt_BR';
    $settings->contact_email = 'test@test.com';
    $settings->social_links = [];
    $settings->save();
});

describe('GeneralSettings helpers', function (): void {
    it('parsedKeywords retorna array de keywords do campo CSV', function (): void {
        $settings = app(GeneralSettings::class);

        expect($settings->parsedKeywords())
            ->toBeArray()
            ->toBe(['laravel', 'php', 'teste']);
    });

    it('parsedKeywords retorna array vazio quando site_keywords é nulo', function (): void {
        $settings = app(GeneralSettings::class);
        $settings->site_keywords = null;
        $settings->save();

        expect($settings->parsedKeywords())->toBe([]);
    });

    it('parsedKeywords remove espaços extras', function (): void {
        $settings = app(GeneralSettings::class);
        $settings->site_keywords = ' laravel , php , teste ';
        $settings->save();

        expect($settings->parsedKeywords())
            ->toBe(['laravel', 'php', 'teste']);
    });

    it('ogImageUrl retorna asset de storage quando og_image está definido', function (): void {
        $settings = app(GeneralSettings::class);
        $settings->og_image = 'settings/og/image.png';
        $settings->save();

        expect($settings->ogImageUrl())
            ->toContain('storage/settings/og/image.png');
    });

    it('ogImageUrl retorna logo padrão quando og_image é nulo', function (): void {
        expect(app(GeneralSettings::class)->ogImageUrl())
            ->toContain('images/logo.png');
    });

    it('activeSocialLinks filtra links vazios', function (): void {
        $settings = app(GeneralSettings::class);
        $settings->social_links = [
            'github' => 'https://github.com/test',
            'linkedin' => '',
            'twitter' => 'https://twitter.com/test',
        ];
        $settings->save();

        expect($settings->activeSocialLinks())
            ->toHaveCount(2)
            ->toHaveKey('github')
            ->toHaveKey('twitter')
            ->not->toHaveKey('linkedin');
    });
});

describe('PageSeoBuilder', function (): void {
    it('build retorna SeoData com valores de GeneralSettings como fallback', function (): void {
        $builder = app(PageSeoBuilder::class);
        $seo = $builder->build(route: 'home');

        expect($seo)->toBeInstanceOf(SeoData::class)
            ->and($seo->title)->toBe('Test Site')
            ->and($seo->description)->toBe('Test description for SEO')
            ->and($seo->keywords)->toBe(['laravel', 'php', 'teste'])
            ->and($seo->locale)->toBe('pt_BR')
            ->and($seo->type)->toBe('website');
    });

    it('build usa título e descrição quando fornecidos', function (): void {
        $builder = app(PageSeoBuilder::class);
        $seo = $builder->build(
            route: 'home',
            title: 'Título Customizado',
            description: 'Descrição customizada'
        );

        expect($seo->title)->toBe('Título Customizado')
            ->and($seo->description)->toBe('Descrição customizada');
    });

    it('build usa keywords fornecidas em vez das de settings', function (): void {
        $builder = app(PageSeoBuilder::class);
        $seo = $builder->build(
            route: 'home',
            keywords: ['custom', 'keywords']
        );

        expect($seo->keywords)->toBe(['custom', 'keywords']);
    });

    it('build gera breadcrumbs automáticos quando não fornecido', function (): void {
        $builder = app(PageSeoBuilder::class);
        $seo = $builder->build(route: 'home', title: 'Home');

        expect($seo->breadcrumbs)->toBeArray()
            ->and($seo->breadcrumbs)->toHaveCount(2);
    });
});

describe('SeoData', function (): void {
    it('toArray inclui noIndex como false por padrão', function (): void {
        $seo = new SeoData(
            title: 'Test',
            description: 'Desc',
            image: 'https://example.com/img.png',
            url: 'https://example.com',
        );

        expect($seo->toArray())->not->toHaveKey('noIndex');
    });

    it('withoutIndexing retorna nova instância com noIndex=true e robots noindex', function (): void {
        $seo = new SeoData(
            title: 'Test',
            description: 'Desc',
            image: 'https://example.com/img.png',
            url: 'https://example.com',
        );

        $noIndexSeo = $seo->withoutIndexing();

        expect($noIndexSeo->noIndex)->toBeTrue()
            ->and($noIndexSeo->robots)->toBe('noindex, nofollow')
            ->and($seo->noIndex)->toBeFalse();
    });

    it('toArray usa url como canonical quando canonical é nulo', function (): void {
        $seo = new SeoData(
            title: 'Test',
            description: 'Desc',
            image: 'img.png',
            url: 'https://example.com/page',
        );

        expect($seo->toArray()['canonical'])->toBe('https://example.com/page');
    });
});

describe('SeoService', function (): void {
    it('forPage resolve SeoData via DI', function (): void {
        $service = app(SeoService::class);
        $seo = $service->forPage(route: 'home', title: 'Home');

        expect($seo)->toBeInstanceOf(SeoData::class)
            ->and($seo->title)->toBe('Home');
    });
});
