<?php

namespace Database\Factories;

use App\Models\ServiceReportService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceReportServiceFactory extends Factory
{
    protected $model = ServiceReportService::class;

    public function definition()
    {
        return [
            'provided_on' => $this->faker->unique()->date(),
            'hours' => $this->faker->randomFloat(2, 1, 8),
            'kilometres' => $this->faker->numberBetween(0, 100),
        ];
    }
}
