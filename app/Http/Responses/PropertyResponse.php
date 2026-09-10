<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResponse extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'city' => $this->resource->city,
            'best_offer' => [
                'id' => (int) $this->resource->best_offer_id,
                'supplier' => $this->resource->best_offer_supplier,
                'price' => (int) $this->resource->best_offer_price,
                'currency' => $this->resource->best_offer_currency,
                'available_units' => (int) $this->resource->best_offer_available_units,
                'expires_at' => Carbon::parse($this->resource->best_offer_expires_at)->toISOString(),
            ],
        ];
    }
}
