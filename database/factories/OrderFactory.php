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

        if ($user->role == 'owner')
            $branchId = 1;
        else
            $branchId = $user->branch_id;

        $status = $this->faker->numberBetween(1, 4);

        if ($status == 1 || $status == 4)
            $isOpen = 1;
        else
            $isOpen = 0;

        return [
            'ref_no' => 'O/' . $this->faker->unique()->randomNumber(6),
            'branch_id' => $branchId,
            'user_id' => $user->id,
            'status' => $status,
            'amount' => $this->faker->randomFloat(2, 2_000_000, 10_000_000),
            'notes' => $this->faker->text,
            'is_open' => $isOpen,
            'created' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
