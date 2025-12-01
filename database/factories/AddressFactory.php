<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class AddressFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->randomElement(['Home', 'Office']),
            'contact_name' => fake()->name(),
            'line1' => fake()->streetAddress(),
            'line2' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'TR',
            'phone' => fake()->phoneNumber(),
            'type' => fake()->randomElement(['shipping', 'billing']),
            'is_primary' => fake()->boolean(),
            'metadata' => ['notes' => fake()->sentence()],
        ];
    }
}
