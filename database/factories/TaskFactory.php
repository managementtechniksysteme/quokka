<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'due_on' => $this->faker->optional()->date(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => 'new',
            'billed' => 'no',
            'private' => false,
            'comment' => $this->faker->optional()->realText(),
            'project_id' => Project::factory(),
            'employee_id' => Employee::factory(),
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn () => ['status' => 'in progress']);
    }

    public function finished(): static
    {
        return $this->state(fn () => ['status' => 'finished', 'ends_on' => $this->faker->date()]);
    }

    public function private(): static
    {
        return $this->state(fn () => ['private' => true]);
    }
}
