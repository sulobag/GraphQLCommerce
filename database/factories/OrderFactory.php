<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => fake()->randomFloat(2, 100, 5000),
            'currency' => 'TRY',
            'checkout_token' => (string) \Illuminate\Support\Str::uuid(),
            'metadata' => [],
        ];
    }
}
