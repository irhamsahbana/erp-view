<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
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
            'name' => $this->faker->city() . ' - ' . rand(1000, 9999)
        ];
    }
}
