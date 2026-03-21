<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'MC - Marketing & Code');
        $this->migrator->add('general.site_description', 'Soluções em marketing digital, SEO, Laravel e desenvolvimento web de alta performance.');
        $this->migrator->add('general.site_keywords', 'marketing digital, laravel, seo, desenvolvimento web, vuejs, inertiajs');
        $this->migrator->add('general.og_image', null);
        $this->migrator->add('general.social_links', [
            'instagram' => 'https://instagram.com/mktcode',
            'linkedin' => 'https://linkedin.com/company/mktcode',
            'github' => 'https://github.com/mktcode',
        ]);
        $this->migrator->add('general.contact_email', 'contato@mktcode.com.br');
    }
};
