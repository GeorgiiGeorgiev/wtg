<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PropertyService
{
    private const PER_PAGE = 15;

    public function search(array $filters): LengthAwarePaginator
    {
        $checkIn = Carbon::createFromFormat('Y-m-d', $filters['check_in'])->startOfDay();
        $checkOut = Carbon::createFromFormat('Y-m-d', $filters['check_out'])->startOfDay();

        $offers = Offer::query()
            ->select([
                'offers.id',
                'offers.property_id',
                'offers.supplier_id',
                'offers.price',
                'offers.currency',
                'offers.available_units',
                'offers.expires_at',
            ])
            ->selectRaw(
                'ROW_NUMBER() OVER (PARTITION BY offers.property_id ORDER BY offers.price, offers.id) AS offer_rank',
            )
            ->where('offers.check_in', $checkIn)
            ->where('offers.check_out', $checkOut)
            ->where('offers.max_guests', '>=', $filters['guests'])
            ->where('offers.available_units', '>', 0)
            ->where('offers.expires_at', '>', now());

        return Property::query()
            ->joinSub($offers, 'best_offer', function ($join): void {
                $join->on('best_offer.property_id', '=', 'properties.id');
            })
            ->join('suppliers', 'suppliers.id', '=', 'best_offer.supplier_id')
            ->where('best_offer.offer_rank', 1)
            ->when(
                $filters['city'] ?? null,
                fn ($query, $city) => $query->where('properties.city', $city),
            )
            ->select([
                'properties.id',
                'properties.code',
                'properties.name',
                'properties.city',
                'best_offer.id as best_offer_id',
                'suppliers.code as best_offer_supplier',
                'best_offer.price as best_offer_price',
                'best_offer.currency as best_offer_currency',
                'best_offer.available_units as best_offer_available_units',
                'best_offer.expires_at as best_offer_expires_at',
            ])
            ->orderBy('best_offer.price')
            ->orderBy('properties.id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }
}
