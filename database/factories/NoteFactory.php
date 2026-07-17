<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'comment' => $this->faker->paragraph(),
            'employee_id' => Employee::factory(),
        ];
    }
}
