<?php

namespace Database\Factories;

use App\Models\Import;
use App\Models\Property;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    public function definition(): array
    {
        $checkIn = now()->addMonth();

        return [
            'supplier_id' => Supplier::factory(),
            'property_id' => Property::factory(),
            'import_id' => Import::factory(),
            'external_id' => fake()->unique()->uuid(),
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkIn->copy()->addDays(5)->toDateString(),
            'max_guests' => 4,
            'price' => fake()->numberBetween(10000, 100000),
            'currency' => 'EUR',
            'available_units' => 2,
            'expires_at' => now()->addWeek(),
        ];
    }
}
