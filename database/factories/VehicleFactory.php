<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'make' => $this->faker->company,
            'model' => $this->faker->word,
            'registration_identifier' => $this->faker->unique()->bothify('??-####'),
            'private' => false,
            'comment' => $this->faker->optional()->sentence,
        ];
    }
}
