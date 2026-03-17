<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'icon' => $this->faker->randomElement(['monitor', 'layers', 'layout', 'globe', 'search-code']),
            'features' => [
                ['item' => $this->faker->sentence()],
                ['item' => $this->faker->sentence()],
            ],
            'ideal_for' => $this->faker->sentence(10),
            'active' => true,
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
