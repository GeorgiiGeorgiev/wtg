<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function create(Offer $offer, array $data): Reservation
    {
        return DB::transaction(function () use ($offer, $data): Reservation {
            $offer = Offer::lockForUpdate()->findOrFail($offer->id);

            if ($offer->available_units < 1 || $offer->expires_at->isPast()) {
                throw ValidationException::withMessages([
                    'offer' => 'The offer is no longer available.',
                ]);
            }

            $offer->decrement('available_units');

            return $offer->reservations()->create($data);
        });
    }
}
