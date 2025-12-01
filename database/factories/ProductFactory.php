<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => strtoupper(fake()->bothify('SKU-#####')),
            'slug' => fake()->unique()->slug(),
            'title' => fake()->productName(),
            'brand' => fake()->company(),
            'category' => fake()->randomElement(['electronics', 'fashion', 'home']),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 100, 5000),
            'currency' => 'TRY',
            'is_active' => true,
            'metadata' => ['warranty' => fake()->randomElement([12, 24, 36])],
            'search_tags' => fake()->words(4),
            'primary_image_url' => fake()->imageUrl(),
        ];
    }
}
