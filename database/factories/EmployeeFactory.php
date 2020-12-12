<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Emoployee::class;

    public function definition()
    {
        return [
            'person_id' => Person::factory(),
            'entered_on' => $this->faker->date(),
            'left_on' => $this->faker->optional()->date(),
            'holidays' => $this->faker->numberBetween(0, 100),
        ];
    }
}
