<?php

declare(strict_types=1);

namespace App\Application\Identity\Queries;

use App\Domain\Identity\Contracts\UserRepository;
use App\Models\User;
use Illuminate\Support\LazyCollection;

class ListPublicUsersForSitemapQuery
{
    public function __construct(private readonly UserRepository $users) {}

    /**
     * @return LazyCollection<int, User>
     */
    public function cursor(): LazyCollection
    {
        return $this->users->cursorPublic();
    }
}
