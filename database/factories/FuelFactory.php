<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Vehicle;

class FuelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $vehicleIds = Vehicle::all()->pluck('id')->toArray();

        return [
            'branch_id' => $this->faker->numberBetween(1, 2),
            'vehicle_id' => $this->faker->randomElement($vehicleIds),
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'is_open' => $this->faker->boolean,
            'created' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
