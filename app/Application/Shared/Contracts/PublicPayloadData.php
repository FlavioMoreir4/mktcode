<?php

declare(strict_types=1);

namespace App\Application\Shared\Contracts;

interface PublicPayloadData
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
