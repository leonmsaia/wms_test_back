<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Wraps a paginated list of users and preserves pagination meta.
 * 
 * @author  Leon. M. Saia
 * @since   2025-08-12
 */
class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    /**
     * Add pagination meta automatically (length-aware paginator).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'per_page'     => $this->resource->perPage(),
                'total'        => $this->resource->total(),
                'last_page'    => $this->resource->lastPage(),
            ],
        ];
    }
}
