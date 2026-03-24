<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\Application\Shared\Contracts\PublicPayloadData;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class PublicResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    protected function payload(): array
    {
        /** @var PublicPayloadData|array<string, mixed> $resource */
        $resource = $this->resource;

        if (is_array($resource)) {
            return $resource;
        }

        return $resource->toArray();
    }
}
