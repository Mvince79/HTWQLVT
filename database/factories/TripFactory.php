<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        $driver = Driver::inRandomOrder()->first();

        return [
            'driver_id' => $driver ? $driver->id : Driver::factory(),
            'vehicle_id' => rand(1, 10),
            'order_id' => rand(1, 50),
            'start_time' => now()->subHours(rand(1, 12)),
            'end_time' => now()->addHours(rand(1, 12)),
            'status' => fake()->randomElement(['not_started', 'in_progress', 'completed']),
        ];
    }
}