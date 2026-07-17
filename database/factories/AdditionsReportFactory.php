<?php

namespace Database\Factories;

use App\Models\AdditionsReport;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionsReportFactory extends Factory
{
    protected $model = AdditionsReport::class;

    public function definition()
    {
        return [
            'number' => $this->faker->numberBetween(1, 1000),
            'status' => 'new',
            'services_provided_on' => $this->faker->unique()->date(),
            'hours' => 0.5,
            'weather' => $this->faker->randomElement(['sunny', 'cloudy', 'rainy', 'snowy']),
            'minimum_temperature' => 10,
            'maximum_temperature' => 20,
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

    public function withInvolvedEmployees(int $count = 1): static
    {
        return $this->hasAttached(Employee::factory()->count($count), ['employee_type' => 'involved'], 'involvedEmployees');
    }

    public function withPresentPeople(int $count = 1): static
    {
        return $this->hasAttached(Person::factory()->count($count), ['person_type' => 'present'], 'presentPeople');
    }
}
