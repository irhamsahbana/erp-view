<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    Branch,
    Project,
    Material,
    Driver,
};

class MaterialMutationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $branches = Branch::inRandomOrder();
        $branchId = $branches->first()->id;

        $projects = Project::where('branch_id', $branchId)->inRandomOrder()->get();
        $projectId = $projects->first()->id;

        $materialIds = Material::all()->pluck('id')->toArray();
        $driverIds = Driver::where('branch_id', $branchId)->pluck('id')->toArray();

        return [
            'branch_id' => $branchId,
            'project_id' => $projectId,
            'material_id' => $this->faker->randomElement($materialIds),
            'driver_id' => $this->faker->randomElement($driverIds),
            'type' => $this->faker->boolean,
            'material_price' => $this->faker->randomFloat(2, 2000000, 10000000),
            'volume' => $this->faker->randomFloat(2, 100, 300),
            'cost' => $this->faker->randomFloat(2, 2000000, 10000000),
            'is_open' => $this->faker->boolean,
            'created' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
