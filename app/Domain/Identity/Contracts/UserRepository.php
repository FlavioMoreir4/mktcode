<?php

declare(strict_types=1);

namespace App\Domain\Identity\Contracts;

use App\Models\User;
use Illuminate\Support\LazyCollection;

interface UserRepository
{
    public function findPublicByUsername(string $username): ?User;

    /**
     * @return LazyCollection<int, User>
     */
    public function cursorPublic(): LazyCollection;
}
