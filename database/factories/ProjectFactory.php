<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'starts_on' => $this->faker->optional()->date(),
            'ends_on' => $this->faker->optional()->date(),
            'comment' => $this->faker->optional()->realText(),
        ];
    }
}
