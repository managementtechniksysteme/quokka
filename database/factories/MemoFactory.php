<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Memo;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemoFactory extends Factory
{
    protected $model = Memo::class;

    public function definition()
    {
        return [
            'number' => $this->faker->unique()->randomNumber(),
            'draft' => false,
            'title' => $this->faker->unique()->sentence,
            'meeting_held_on' => $this->faker->date(),
            'next_meeting_on' => $this->faker->optional()->date(),
            'comment' => $this->faker->realText(),
            'project_id' => Project::factory(),
            'employee_id' => Employee::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['draft' => true]);
    }
}
