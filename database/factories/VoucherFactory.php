<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    Branch,
    User,
    Order,
    Voucher
};

class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $branchId = Branch::inRandomOrder()->first()->id;
        $userId = User::where('branch_id', $branchId)->inRandomOrder()->first()->id;

        $type = $this->faker->randomElement([Voucher::TYPE_VOUCHER_INCOME, Voucher::TYPE_VOUCHER_EXPENSE]);
        $status = $this->faker->randomElement([Voucher::STATUS_VOUCHER_URGENT, Voucher::STATUS_VOUCHER_BY_PLANNING]);

        if ($status == Voucher::STATUS_VOUCHER_URGENT || $type == Voucher::TYPE_VOUCHER_INCOME)
            $orderId = null;
        else
            $orderId = Order::where('branch_id', $branchId)
                            ->where('status', Order::STATUS_ORDER_ACCEPTED)
                            ->inRandomOrder()->first()->id;

        if ($type == Voucher::TYPE_VOUCHER_INCOME)
            $status = null;

        return [
            'ref_no' => 'V/' . $this->faker->unique()->randomNumber(6),
            'branch_id' => $branchId,
            'user_id' => $userId,
            'order_id' => $orderId,
            'status' => $status,
            'type' => $type,
            'amount' => $this->faker->randomFloat(2, 2_000_000, 10_000_000),
            'notes' => $this->faker->sentence,
            'is_open' => $this->faker->boolean(),
            'created' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
