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
            'user_id' => User::factory()->state(['role' => 'driver']),
            'phone' => fake()->unique()->phoneNumber(),
            'license_number' => fake()->unique()->bothify('GPLX#####'),
            'license_type' => fake()->randomElement(['B2', 'C', 'FC']),
            'license_expiry' => fake()->date(),
            'status' => 'approved',
            'working_status' => 'idle',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
