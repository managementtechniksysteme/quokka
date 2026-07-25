<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskCommentFactory extends Factory
{
    protected $model = TaskComment::class;

    public function definition()
    {
        return [
            'comment' => $this->faker->realText,
            'task_id' => Task::factory(),
            'employee_id' => Employee::factory(),
        ];
    }
}
