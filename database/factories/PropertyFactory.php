<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('property-####'),
            'name' => fake()->sentence(3),
            'city' => fake()->city(),
        ];
    }
}
