<?php
namespace Database\Factories;

use App\Models\Address;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'starts_on' => $this->faker->optional()->date(),
            'ends_on' => $this->faker->optional()->date(),
            'due_on' => $this->faker->optional()->date(),
            'private' => $this->faker->boolean,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['new', 'in progress', 'finished']),
            'billed' => $this->faker->randomElement(['yes', 'no', 'warranty']),
            'comment' => $this->faker->optional()->realText(),
        ];
    }
}
