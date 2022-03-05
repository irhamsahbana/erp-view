<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nomorKendaraan' => $this->faker->unique()->randomNumber(6),
            'cabangId' => 1,

            'createdAt' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'updatedAt' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
