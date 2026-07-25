<?php

namespace Database\Factories;

use App\Models\WageService;
use Illuminate\Database\Eloquent\Factories\Factory;

class WageServiceFactory extends Factory
{
    protected $model = WageService::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence,
            'unit' => $this->faker->randomElement(['h', 'Stk', 'km']),
            'costs' => $this->faker->randomFloat(1, 0, 200),
        ];
    }
}
