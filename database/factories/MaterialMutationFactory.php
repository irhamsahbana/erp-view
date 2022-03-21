<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    Branch,
    Project,
    Material,
    Driver,
    MaterialMutation
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
        $type = $this->faker->randomElement([MaterialMutation::TYPE_IN, MaterialMutation::TYPE_OUT]);

        if ($type == MaterialMutation::TYPE_IN) {
            $materialPrice = $this->faker->randomFloat(2, 2_000_000, 5_000_000);
        } else {
            $materialPrice = 0;
        }

        return [
            'branch_id' => $branchId,
            'project_id' => $projectId,
            'material_id' => $this->faker->randomElement($materialIds),
            'type' => $type,
            'material_price' => $materialPrice,
            'volume' => $this->faker->randomFloat(2, 100, 300),
            'is_open' => $this->faker->boolean,
            'created' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
