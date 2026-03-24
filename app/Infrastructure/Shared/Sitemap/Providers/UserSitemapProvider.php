<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Sitemap\Providers;

use App\Application\Identity\Queries\ListPublicUsersForSitemapQuery;
use App\Domain\Shared\Contracts\SitemapEntryProvider;
use App\Models\User;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class UserSitemapProvider implements SitemapEntryProvider
{
    public function __construct(private readonly ListPublicUsersForSitemapQuery $users) {}

    public function generate(Sitemap $sitemap): void
    {
        $this->users
            ->cursor()
            ->each(function (User $user) use ($sitemap): void {
                $url = Url::create(route('public.user.show', $user->username))
                    ->setLastModificationDate($user->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6);

                $cover = $user->getFirstMedia('profile_photo');
                if ($cover) {
                    $url->addImage($cover->getUrl(), $user->title ?? $user->name);
                }

                $sitemap->add($url);
            });
    }

    public function filename(): string
    {
        return 'sitemap-users.xml';
    }
}
