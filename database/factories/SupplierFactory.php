<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('supplier-####'),
            'name' => fake()->company(),
        ];
    }
}
