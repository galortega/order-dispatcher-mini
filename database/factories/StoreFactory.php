<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;

class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Store::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'latitude' => $this->faker->latitude(-2.08648, -2.13520),
            'longitude' => $this->faker->longitude(-79.89877, -79.88315),
        ];
    }
}