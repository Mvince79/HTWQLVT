<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),

            'code' => 'ORD-' . now()->format('Ymd') . '-' . $this->faker->unique()->numberBetween(100, 999),

            'cargo_name' => $this->faker->randomElement([
                'Điện tử',
                'Nội thất',
                'Quần áo',
                'Thực phẩm',
                'Vật liệu xây dựng',
            ]),

            'cargo_weight' => $this->faker->randomFloat(2, 100, 10000),

            'price' => $this->faker->randomFloat(2, 500000, 50000000),

            'pickup_location' => $this->faker->address(),
            'delivery_location' => $this->faker->address(),

            'scheduled_date' => $this->faker->dateTimeBetween('now', '+7 days'),

            'status' => $this->faker->randomElement([
                'pending',
                'assigned',
                'delivering',
                'completed',
                'cancelled'
            ]),
        ];
    }
}
