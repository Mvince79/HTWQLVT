<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_plate' => fake()->unique()->bothify('##A-#####'),
            'type' => fake()->randomElement(['light_truck', 'medium_truck', 'heavy_truck', 'container_truck']),
            'is_active' => true,
            'working_status' => 'available',
        ];
    }
}
