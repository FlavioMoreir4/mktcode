<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @mixin \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Post>
 */
class PublicPostCollection extends ResourceCollection
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => PublicPostResource::collection($this->collection),

            // 'meta' => [
            //     'current_page' => $this->currentPage(),
            //     'last_page' => $this->lastPage(),
            //     'per_page' => $this->perPage(),
            //     'total' => $this->total(),
            // ],

            // 'links' => [
            //     'next' => $this->nextPageUrl(),
            //     'prev' => $this->previousPageUrl(),
            // ],
        ];
    }
}
