<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera todos os sitemaps do site.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gerando sitemaps...');
        $this->generatePages();
        $this->generatePosts();
        $this->generateProjects();
        $this->generateUsers();
        $this->generateIndex();
    }

    private function generatePages(): void
    {
        Sitemap::create()
            ->add(Url::create(route('home'))
                ->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Url::create(route('public.services'))
                ->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('public.projects'))
                ->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Url::create(route('public.blog.index'))
                ->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create(route('public.about'))
                ->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('public.contact'))
                ->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
            ->writeToFile(public_path('sitemap-pages.xml'));

        $this->line('  ✓ pages');
    }

    private function generatePosts(): void
    {
        $sitemap = Sitemap::create();

        Post::published()
            ->with('media')
            ->latest('published_at')
            ->cursor()
            ->each(fn (Post $post) => $sitemap->add($post));

        $sitemap->writeToFile(public_path('sitemap-posts.xml'));
        $this->line('  ✓ posts');
    }

    private function generateProjects(): void
    {
        $sitemap = Sitemap::create();

        Project::published()
            ->with('media')
            ->ordered()
            ->cursor()
            ->each(fn (Project $project) => $sitemap->add($project));

        $sitemap->writeToFile(public_path('sitemap-projects.xml'));
        $this->line('  ✓ projects');
    }

    private function generateUsers(): void
    {
        $sitemap = Sitemap::create();

        User::with('media')
            ->cursor()
            ->each(fn (User $user) => $sitemap->add($user));

        $sitemap->writeToFile(public_path('sitemap-users.xml'));
        $this->line('  ✓ users');
    }

    private function generateIndex(): void
    {
        SitemapIndex::create()
            ->add(url('sitemap-pages.xml'))
            ->add(url('sitemap-posts.xml'))
            ->add(url('sitemap-projects.xml'))
            ->add(url('sitemap-users.xml'))
            ->writeToFile(public_path('sitemap.xml'));

        $this->line('  ✓ index');
    }
}
