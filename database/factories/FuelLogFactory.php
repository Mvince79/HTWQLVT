<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FuelLog>
 */
class FuelLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => \App\Models\Vehicle::inRandomOrder()->first()->id,
            'driver_id' => \App\Models\Driver::inRandomOrder()->first()->id,
            'trip_id' => \App\Models\Trip::inRandomOrder()->first()->id,
            'fuel_amount' => fake()->randomFloat(2, 10, 100),
            'note' => fake()->sentence(),
            'created_at' => now(),
        ];
    }
}
