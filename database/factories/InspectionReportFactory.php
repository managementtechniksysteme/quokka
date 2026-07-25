<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\InspectionReport;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionReportFactory extends Factory
{
    protected $model = InspectionReport::class;

    public function definition()
    {
        return [
            'status' => 'new',
            'inspected_on' => $this->faker->unique()->date(),
            'weather' => $this->faker->randomElement(['sunny', 'cloudy', 'rainy', 'snowy']),
            'equipment_type' => $this->faker->word(),
            'equipment_identifier' => $this->faker->unique()->bothify('EQ-####'),
            'uvc_lamp_type' => $this->faker->word(),
            'uvc_lamp_quantity' => 1,
            'uvc_lamp_operating_hours' => 100,
            'uvc_lamp_impulses' => 10,
            'uvc_lamp_uv_intensity_arrival' => 50,
            'uvc_lamp_uv_intensity_departure' => 45,
            'uvc_lamp_values_unit' => 'percent',
            'uvc_lamp_replacement_available' => false,
            'uvc_sensor_type' => $this->faker->word(),
            'uvc_sensor_identifier' => $this->faker->bothify('SN-####'),
            'uvc_sensor_pre_alarm' => 30,
            'uvc_sensor_cut_off_point' => 20,
            'uvc_sensor_values_unit' => 'percent',
            'quartz_tube_contaminated' => false,
            'quartz_tube_leaking' => false,
            'water_suspended_load_visible' => false,
            'water_air_bubble_free' => true,
            'water_flow_rate' => 10,
            'water_minimum_uv_transmission' => 80,
            'water_measured_uv_transmission' => 90,
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
