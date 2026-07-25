<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Logbook;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogbookFactory extends Factory
{
    protected $model = Logbook::class;

    public function definition(): array
    {
        $startKilometres = $this->faker->numberBetween(0, 100000);
        $drivenKilometres = $this->faker->numberBetween(1, 200);

        return [
            'driven_on' => $this->faker->date(),
            'start_kilometres' => $startKilometres,
            'end_kilometres' => $startKilometres + $drivenKilometres,
            'driven_kilometres' => $drivenKilometres,
            'litres_refuelled' => $this->faker->optional()->numberBetween(1, 80),
            'origin' => $this->faker->city,
            'destination' => $this->faker->city,
            'comment' => $this->faker->optional()->sentence,
            'employee_id' => Employee::factory(),
            'vehicle_id' => Vehicle::factory(),
        ];
    }
}
