<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => \App\Models\Trip::inRandomOrder()->first()->id,
            'driver_id' => \App\Models\Driver::inRandomOrder()->first()->id,
            'type' => fake()->randomElement(['Tai nạn', 'Hỏng xe', 'Thủng lốp']),
            'note' => fake()->text(),
        ];
    }
}
