<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResponse extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'offer_id' => $this->resource->offer_id,
            'client_reference' => $this->resource->client_reference,
            'customer_name' => $this->resource->customer_name,
            'customer_email' => $this->resource->customer_email,
            'created_at' => $this->resource->created_at->toISOString(),
        ];
    }
}
