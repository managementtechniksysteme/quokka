<?php

namespace Database\Factories;

use App\Models\Accounting;
use App\Models\Employee;
use App\Models\MaterialService;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountingFactory extends Factory
{
    protected $model = Accounting::class;

    public function definition(): array
    {
        return [
            'service_provided_on' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 1, 10),
            'comment' => $this->faker->optional()->sentence,
            'employee_id' => Employee::factory(),
            'project_id' => Project::factory(),
            'service_id' => MaterialService::factory(),
        ];
    }
}
