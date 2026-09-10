<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowImportResponse extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'supplier' => $this->resource->supplier->code,
            'external_import_id' => $this->resource->external_import_id,
            'sent_at' => $this->resource->sent_at->toISOString(),
            'status' => $this->resource->status,
            'total_offers' => $this->resource->total_offers,
            'processed_offers' => $this->resource->processed_offers,
            'error' => $this->resource->error,
            'created_at' => $this->resource->created_at->toISOString(),
            'completed_at' => $this->resource->completed_at?->toISOString(),
        ];
    }
}
