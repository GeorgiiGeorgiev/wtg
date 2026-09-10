<?php

namespace App\Services;

use App\Models\Import;
use App\Models\Offer;
use App\Models\Property;

class OfferService
{
    public function store(Import $import, array $data): void
    {
        $offer = Offer::where('supplier_id', $import->supplier_id)
            ->where('external_id', $data['external_id'])
            ->lockForUpdate()
            ->first();

        if ($offer?->import()->where('sent_at', '>', $import->sent_at)->exists()) {
            return;
        }

        $property = Property::firstOrCreate(
            ['code' => $data['property']['code']],
            [
                'name' => $data['property']['name'],
                'city' => $data['property']['city'],
            ],
        );

        $attributes = [
            'property_id' => $property->id,
            'import_id' => $import->id,
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'max_guests' => $data['max_guests'],
            'price' => $data['price'],
            'currency' => $data['currency'],
            'available_units' => $data['available_units'],
            'expires_at' => $data['expires_at'],
        ];

        if ($offer) {
            $offer->update($attributes);

            return;
        }

        Offer::create([
            'supplier_id' => $import->supplier_id,
            'external_id' => $data['external_id'],
            ...$attributes,
        ]);
    }
}
