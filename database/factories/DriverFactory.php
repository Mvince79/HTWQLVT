<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'user_id' => User::factory(), // tự tạo user luôn

        'phone' => fake()->phoneNumber(),
        'license_number' => fake()->unique()->bothify('GPLX#####'),
        'license_type' => fake()->randomElement(['B2', 'C', 'FC']),
        'license_expiry' => fake()->date(),

        'vehicle_type' => fake()->randomElement(['truck', 'container', 'tanker']),
        'plate_number' => fake()->bothify('##A-#####'),

        'working_status' => fake()->randomElement([
            'idle', 'assigned', 'delivering'
        ]),
    ];
}
}
