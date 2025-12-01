<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class InventoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'available_quantity' => fake()->numberBetween(25, 200),
            'reserved_quantity' => 0,
            'safety_threshold' => 5,
        ];
    }
}
