<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ServiceReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceReportFactory extends Factory
{
    protected $model = ServiceReport::class;

    public function definition()
    {
        return [
            'number' => $this->faker->numberBetween(1, 1000),
            'status' => 'new',
            'comment' => $this->faker->realText(),
            'project_id' => Project::factory(),
            'employee_id' => Employee::factory(),
        ];
    }

    public function asNew(): static
    {
        return $this->state(fn () => ['status' => 'new']);
    }

    public function signed(): static
    {
        return $this->state(fn () => ['status' => 'signed']);
    }

    public function finished(): static
    {
        return $this->state(fn () => ['status' => 'finished']);
    }

    public function withServices(int $count = 1): static
    {
        return $this->has(ServiceReportServiceFactory::new()->count($count), 'services');
    }
}
