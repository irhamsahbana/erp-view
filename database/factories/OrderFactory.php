<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = \App\Models\User::all();
        $user = $users->random();

        if ($user->role == 'owner') {
            $branchId = 1;
        } else {
            $branchId = $user->branch_id;
        }

        $notes = [null, $this->faker->sentence(5)];

        return [
            'branch_id' => $branchId,
            'user_id' => $user->id,
            'status' => $this->faker->numberBetween(1, 5),
            'amount' => $this->faker->randomFloat(2, 2_000_000, 10_000_000),
            'notes' => $this->faker->randomElement($notes),
            'is_open' => $this->faker->boolean,
            'created' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
