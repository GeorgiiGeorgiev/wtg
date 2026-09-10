<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'offer_id' => Offer::factory(),
            'client_reference' => fake()->unique()->uuid(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
        ];
    }
}
