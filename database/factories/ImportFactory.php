<?php

namespace Database\Factories;

use App\Models\Import;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'external_import_id' => fake()->unique()->uuid(),
            'sent_at' => now(),
            'status' => Import::STATUS_PENDING,
            'total_offers' => 0,
            'processed_offers' => 0,
            'payload' => [],
        ];
    }
}
