<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class OrderItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->numberBetween(1, 3),
            'unit_price' => fake()->randomFloat(2, 100, 5000),
            'currency' => 'TRY',
            'snapshot' => [],
        ];
    }
}
