<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PaymentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider' => 'fakepay',
            'status' => 'pending',
            'reference' => strtoupper(fake()->bothify('PAY-######')),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'currency' => 'TRY',
            'payload' => ['channel' => 'graphql'],
        ];
    }
}
