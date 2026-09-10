<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PropertyCollectionResponse extends ResourceCollection
{
    public $collects = PropertyResponse::class;

    public function with(Request $request): array
    {
        return [
            'next' => $this->resource->nextPageUrl(),
            'prev' => $this->resource->previousPageUrl(),
            'per_page' => $this->resource->perPage(),
        ];
    }
}
