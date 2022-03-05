<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Kendaraan;

class SolarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $kendaraanIds = Kendaraan::all()->pluck('id')->toArray();

        return [
            'tanggal' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'cabangId' => 1,
            'KendaraanId' => $this->faker->randomElement($kendaraanIds),
            'jumlah' => $this->faker->randomFloat(10, 0, 100),
            'statusClose' => 0,

            'createdAt' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'updatedAt' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
