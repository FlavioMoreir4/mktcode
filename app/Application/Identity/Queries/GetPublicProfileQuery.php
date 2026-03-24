<?php

declare(strict_types=1);

namespace App\Application\Identity\Queries;

use App\Domain\Identity\Contracts\UserRepository;
use App\Models\User;

class GetPublicProfileQuery
{
    public function __construct(private readonly UserRepository $users) {}

    public function findByUsername(string $username): ?User
    {
        return $this->users->findPublicByUsername($username);
    }
}
