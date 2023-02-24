<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
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
            'name' => $this->faker->name,
            'latitude' => $this->faker->latitude(-2.08648, -2.13520),
            'longitude' => $this->faker->longitude(-79.89877, -79.88315),
            'code' => $this->faker->unique()->randomNumber(4),
            'max_orders' => 2,
        ];
    }
}