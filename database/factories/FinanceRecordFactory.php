<?php

namespace Database\Factories;

use App\Models\FinanceGroup;
use App\Models\FinanceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceRecordFactory extends Factory
{
    protected $model = FinanceRecord::class;

    public function definition()
    {
        return [
            'billed_on' => $this->faker->date(),
            'title' => $this->faker->unique()->words(3, true),
            'comment' => $this->faker->optional()->sentence,
            'amount' => $this->faker->randomFloat(2, -1000, 1000),
            'finance_group_id' => FinanceGroup::factory(),
        ];
    }
}
