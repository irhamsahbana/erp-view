<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'branch_id' => $this->faker->numberBetween(1, 2),
            'license_plate' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        ];
    }
}
