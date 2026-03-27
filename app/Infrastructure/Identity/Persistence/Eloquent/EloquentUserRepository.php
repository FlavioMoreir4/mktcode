<?php

declare(strict_types=1);

namespace App\Infrastructure\Identity\Persistence\Eloquent;

use App\Domain\Content\Enums\PostStatus;
use App\Domain\Identity\Contracts\UserRepository;
use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Support\LazyCollection;

class EloquentUserRepository implements UserRepository
{
    public function findPublicByUsername(string $username): ?User
    {
        return User::query()
            ->where('username', $username)
            ->whereNotNull('username')
            ->where('username', '!=', '')
            ->withCount([
                'posts' => fn ($query) => $query
                    ->where('status', PostStatus::Published)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now()),
                'projects' => fn ($query) => $query
                    ->where('status', ProjectStatus::Published),
            ])
            ->with([
                'posts' => fn ($query) => $query
                    ->where('status', PostStatus::Published)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->with('category')
                    ->latest('published_at')
                    ->limit(5),
                'projects' => fn ($query) => $query
                    ->where('status', ProjectStatus::Published)
                    ->with('media')
                    ->orderByDesc('featured')
                    ->orderBy('sort_order')
                    ->orderByDesc('created_at')
                    ->limit(6),
            ])
            ->first();
    }

    /**
     * @return LazyCollection<int, User>
     */
    public function cursorPublic(): LazyCollection
    {
        return User::query()
            ->with('media')
            ->whereNotNull('username')
            ->where('username', '!=', '')
            ->cursor();
    }
}
