<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PublicProjectCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => PublicProjectIndexResource::collection($this->collection),

            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),

            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),

            'links' => $this->linkCollection(),
        ];
    }
}
