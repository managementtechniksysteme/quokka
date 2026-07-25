<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\FlowMeterInspectionReport;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlowMeterInspectionReportFactory extends Factory
{
    protected $model = FlowMeterInspectionReport::class;

    public function definition()
    {
        return [
            'status' => 'new',
            'inspected_on' => $this->faker->unique()->date(),
            'weather' => $this->faker->randomElement(['sunny', 'cloudy', 'rainy', 'snowy']),
            'temperature' => 15,
            'equipment_identifier' => $this->faker->unique()->bothify('EQ-####'),
            'measuring_point' => 'MP-1',
            'installation_point' => 'IP-1',
            'medium' => 'Abwasser',
            'responsible_person' => $this->faker->name(),
            'responsible_person_instructed_on' => $this->faker->date(),
            'instructor' => $this->faker->name(),
            'profile_outer_diameter' => 200,
            'profile_wall_thickness' => 10,
            'profile_material' => 'PVC',
            'without_cross_section_reduction' => true,
            'fully_filled' => true,
            'speed_measurement_type' => 'radar',
            'documentation_existent' => true,
            'inspection_book_existent' => true,
            'inspection_requirements_existent' => true,
            'documentation_current' => true,
            'q_min' => 1,
            'q_max' => 100,
            'flow_range_type' => 'guess',
            'flow_rate_meter' => 'Meter A',
            'flow_rate_meter_make' => 'Make A',
            'flow_rate_meter_type' => 'Type A',
            'flow_rate_meter_identifier' => 'ID-A',
            'measurement_transformer_point' => 'local',
            'measurement_transformer_make' => 'Make B',
            'measurement_transformer_type' => 'Type B',
            'measurement_transformer_identifier' => 'ID-B',
            'measurement_transformer_level_unit' => 'mA',
            'measurement_transformer_minimum_level' => 4,
            'measurement_transformer_maximum_level' => 20,
            'measurement_transformer_range_100_percent' => 100,
            'measurement_transformer_impulses' => 10,
            'measurement_transformer_data_logging' => 'yes',
            'headwater_pipe_diameter' => 150,
            'headwater_calming_section' => '5m',
            'headwater_calming_section_assessment' => 'ok',
            'measurement_section_installation_according_to_manufacturer' => true,
            'measurement_section_pipe_diameter' => 150,
            'tailwater_pipe_diameter' => 150,
            'tailwater_pipe_fully_filled' => true,
            'tailwater_runout_section_assessment' => 'ok',
            'tailwater_measurement_pipe_can_run_dry' => false,
            'tailwater_flow_conditions_influenced' => false,
            'comparison_measurements_process' => 'volumetric',
            'comparison_measurement_volumetric_basin' => 'Basin A',
            'comparison_measurement_volumetric_basin_cross_section_area' => 5,
            'comparison_measurement_volumetric_height_measurement_equipment' => 'Gauge A',
            'measurement_difference_up_to_30_q_max' => 1,
            'measurement_difference_above_30_q_max' => 1,
            'reading_difference_up_to_30_q_max' => 1,
            'reading_difference_above_30_q_max' => 1,
            'equipment_in_tolerance_range' => true,
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
