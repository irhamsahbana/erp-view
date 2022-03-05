<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Branch;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = ['owner', 'admin', 'branch_head', 'material', 'accountant', 'cashier'];
        $role = $roles[array_rand($roles)];

        if ($role == 'owner') {
            $branchId = null;
        } else {
            $branchId = Branch::all()->random()->id;
        }

        return [
            'branch_id' => $branchId,
            'role' => $role,
            'username' => $this->faker->unique()->userName(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
