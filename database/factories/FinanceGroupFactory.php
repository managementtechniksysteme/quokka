<?php

namespace Database\Factories;

use App\Models\FinanceGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceGroupFactory extends Factory
{
    protected $model = FinanceGroup::class;

    public function definition()
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'comment' => $this->faker->optional()->sentence,
        ];
    }
}
