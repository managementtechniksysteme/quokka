<?php

namespace Database\Factories;

use App\Models\DeliveryNote;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryNoteFactory extends Factory
{
    protected $model = DeliveryNote::class;

    public function definition()
    {
        return [
            'status' => 'new',
            'written_on' => $this->faker->date(),
            'title' => $this->faker->unique()->sentence(3),
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
}
