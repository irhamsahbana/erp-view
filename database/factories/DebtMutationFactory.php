<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Branch;
use App\Models\Project;
use App\Models\Vendor;

class DebtMutationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $branch = Branch::all();
        $branchId = $branch->random()->id;

        $project = Project::where('branch_id', $branchId)->inRandomOrder()->first();
        $vendor = Vendor::where('branch_id', $branchId)->inRandomOrder()->first();

        return [
            'branch_id' => $branchId,
            'project_id' => $project->id,
            'vendor_id' => $vendor->id,
            'type' => rand(1, 2),
            'transaction_type' => rand(1, 2),
            'amount' => rand(5_000_000, 10_000_000),
            'is_open' => rand(0, 1),
            'created' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
        ];
    }
}
