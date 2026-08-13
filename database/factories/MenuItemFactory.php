<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(12),
            'price' => $this->faker->randomFloat(2, 2, 45),
            'category' => $this->faker->randomElement([
                'Entradas', 'Platos Fuertes', 'Postres', 'Bebidas',
            ]),
            'available' => $this->faker->boolean(90),
            'image_url' => null,
        ];
    }
}
