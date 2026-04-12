<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    public function definition(): array
    {
        return [
            'driver_id' => Driver::inRandomOrder()->first()->id ?? Driver::factory(),
            'vehicle_id' => Vehicle::inRandomOrder()->first()->id ?? Vehicle::factory(),
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory(),

            'start_time' => now(),
            'end_time' => now()->addHours(10),
            'status' => 'in_progress',
        ];
    }
}