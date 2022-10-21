<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlowMeterInspectionReportStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $inspected_on = $this->input('inspected_on');
        $project_id = $this->input('project_id');
        $equipment_identifier = $this->input('equipment_identifier');

        $speed_measurement_type = $this->input('speed_measurement_type');
        $measurement_transformer_level_unit = $this->input('measurement_transformer_level_unit');
        $measurement_section_slope = $this->input('measurement_section_slope');
        $measurement_section_pipe_visible_inspection_inside_possible = $this->input('measurement_section_pipe_visible_inspection_inside_possible');
        $tailwater_runout_section_slope = $this->input('tailwater_runout_section_slope');
        $tailwater_flow_conditions_influenced = $this->input('tailwater_flow_conditions_influenced');
        $comparison_measurements_process = $this->input('comparison_measurements_process');

        $rules = [
            'project_id' => 'required|exists:projects,id',
            'inspected_on' => [
                'required',
                'date',
                Rule::unique('flow_meter_inspection_reports')
                    ->where(function ($query) use ($inspected_on, $equipment_identifier, $project_id) {
                        return $query
                            ->where('inspected_on', $inspected_on)
                            ->where('equipment_identifier', $equipment_identifier)
                            ->where('project_id', $project_id);
                    }),
            ],
            'weather' => 'required|in:sunny,cloudy,rainy,snowy',
            'temperature' => 'required|integer',
            'equipment_identifier'=> 'required',
            'area_1' => 'nullable',
            'area_2' => 'nullable',
            'area_3' => 'nullable',
            'treatment_plant_size' => 'integer|min:0|nullable',
            'measuring_point' => 'required',
            'installation_point' => 'required',
            'medium' => 'required',
            'commissioning_year' => 'integer|min:0|nullable',
            'responsible_person' => 'required',
            'responsible_person_instructed_on' => 'required|date',
            'instructor' => 'required',
            'information_providing_people' => 'nullable',
            'last_inspected_on' => 'date|nullable',
            'last_inspected_by' => 'nullable',
            'last_inspection_project' => 'nullable',
            'profile_outer_diameter' => 'required|integer|min:1',
            'profile_wall_thickness' => 'required|integer|min:1',
            'profile_material' => 'required',
            'without_cross_section_reduction' => 'required|boolean',
            'fully_filled' => 'required|boolean',
            'speed_measurement_type' => 'required|in:doppler_ultrasonic,ultrasonic_signal_transmit_time,ultrasonic_cross_correlation,radar,other',
            'water_level_measurement_type' => 'nullable',
            'equipment_changes' => 'nullable',
            'documentation_existent' => 'required|boolean',
            'inspection_book_existent' => 'required|boolean',
            'inspection_requirements_existent' => 'required|boolean',
            'documentation_current' => 'required|boolean',
            'equipment_changes_to_documentation' => 'nullable',
            'measuring_pipe_type' => 'nullable',
            'measuring_pipe_minimum_speed' => 'required_with:measuring_pipe_minimum_speed_unit|numeric|min:0|nullable',
            'measuring_pipe_minimum_speed_unit' => 'required_with:measuring_pipe_minimum_speed|in:m_s|nullable',
            'measuring_pipe_maximum_speed' => 'required_with:measuring_pipe_maximum_speed_unit,measuring_pipe_maximum_flow_rate,measuring_pipe_maximum_flow_rate_unit|numeric|min:0|nullable',
            'measuring_pipe_maximum_speed_unit' => 'required_with:measuring_pipe_maximum_speed,measuring_pipe_maximum_flow_rate,measuring_pipe_maximum_flow_rate_unit|in:m_s|nullable',
            'measuring_pipe_maximum_flow_rate' => 'required_with:measuring_pipe_maximum_speed,measuring_pipe_maximum_speed_unit,measuring_pipe_maximum_flow_rate_unit|numeric|min:0|nullable',
            'measuring_pipe_maximum_flow_rate_unit' => 'required_with:measuring_pipe_maximum_speed,measuring_pipe_maximum_speed_unit,measuring_pipe_maximum_flow_rate|in:l_s,m3_h|nullable',
            'mucus_suppression' => 'numeric|min:0|required_with:mucus_suppression_unit|nullable',
            'mucus_suppression_unit' => 'in:percent,l_s|required_with:mucus_suppression|nullable',
            'q_min' => 'required|numeric|min:0',
            'q_max' => 'required|numeric|min:0',
            'flow_range_type' => 'required|in:guess,statistical_analysis',
            'water_level_meter' => 'nullable',
            'water_level_meter_make' => 'nullable',
            'water_level_meter_type' => 'nullable',
            'water_level_meter_identifier' => 'nullable',
            'flow_rate_meter' => 'required',
            'flow_rate_meter_make' => 'required',
            'flow_rate_meter_type' => 'required',
            'flow_rate_meter_identifier' => 'required',
            'measurement_transformer_point' => 'required|in:local,control_stand',
            'measurement_transformer_make' => 'required',
            'measurement_transformer_type' => 'required',
            'measurement_transformer_identifier' => 'required',
            'measurement_transformer_level_unit' => 'required|in:mA,V,interface',
            'measurement_transformer_range_100_percent' => 'required|numeric|min:0',
            'measurement_transformer_impulses' => 'required|integer|min:0',
            'measurement_transformer_data_logging' => 'required',
            'headwater_pipe_diameter' => 'required|integer|min:1',
            'headwater_calming_section' => 'required',
            'headwater_calming_section_assessment' => 'required',
            'measurement_section_slope' => 'numeric|min:0|required_with:measurement_section_slope_assessment_type|nullable',
            'measurement_section_installation_according_to_manufacturer' => 'required|boolean',
            'measurement_section_minimum_speed_undercut_point' => 'numeric|min:0|nullable',
            'measurement_section_pipe_diameter' => 'required|integer|min:1',
            'measurement_section_access_possible' => 'boolean|nullable',
            'measurement_section_pipe_required_fill_level_existent' => 'boolean|nullable',
            'measurement_section_pipe_visible_inspection_inside_possible' => 'boolean|nullable',
            'measurement_section_pipe_contaminated' => 'boolean|nullable',
            'measurement_section_pipe_cleaning_possible' => 'boolean|nullable',
            'measurement_section_pipe_last_cleaned_on' => 'date|nullable',
            'measurement_section_sensor_cleaned' => 'boolean|nullable',
            'measurement_section_sensor_damaged' => 'boolean|nullable',
            'measurement_section_pipe_inside_surface_ok' => 'boolean|nullable',
            'measurement_section_pipe_grounding_existent' => 'boolean|nullable',
            'measurement_section_pipe_air_pockets_visible' => 'boolean|nullable',
            'tailwater_pipe_diameter' => 'required|integer|min:1',
            'tailwater_pipe_fully_filled' => 'required|boolean',
            'tailwater_runout_section_slope' => 'numeric|min:0|required_with:tailwater_runout_section_slope_assessment_type|nullable',
            'tailwater_runout_section_assessment' => 'required',
            'tailwater_measurement_pipe_can_run_dry' => 'required|boolean',
            'tailwater_flow_conditions_influenced' => 'required|boolean',
            'zero_flow_rate_testing_conditions' => 'required_with:zero_flow_rate_reading_points,zero_flow_rate_displayed_flow|nullable',
            'zero_flow_rate_reading_points' => 'required_with:zero_flow_rate_testing_conditions,zero_flow_rate_displayed_flow|nullable',
            'zero_flow_rate_displayed_flow' => 'required_with:zero_flow_rate_testing_conditions,zero_flow_rate_reading_points|numeric|min:0|nullable',
            'comparison_measurements_process' => 'required|in:mobile_measurement_equipment,volumetric',
            'comparison_measurement_mobile_type' => 'nullable',
            'comparison_measurement_mobile_type_other' => 'nullable',
            'comparison_measurement_mobile_installation_point' => 'nullable',
            'comparison_measurement_mobile_equipment_make' => 'nullable',
            'comparison_measurement_mobile_equipment_type' => 'nullable',
            'comparison_measurement_mobile_equipment_identifier' => 'nullable',
            'comparison_measurement_mobile_equipment_maximum_speed' => 'nullable',
            'comparison_measurement_mobile_equipment_maximum_speed_unit' => 'nullable',
            'comparison_measurement_mobile_equipment_maximum_flow_rate' => 'nullable',
            'comparison_measurement_mobile_equipment_maximum_flow_rate_unit' => 'nullable',
            'comparison_measurement_mobile_equipment_q_min' => 'nullable',
            'comparison_measurement_mobile_equipment_last_calibrated_on' => 'nullable',
            'comparison_measurement_mobile_equipment_last_cal_provider' => 'nullable',
            'comparison_measurement_mobile_equipment_last_cal_doc_identifier' => 'nullable',
            'comparison_measurement_volumetric_basin' => 'nullable',
            'comparison_measurement_volumetric_basin_cross_section_area' => 'nullable',
            'comparison_measurement_volumetric_height_measurement_equipment' => 'nullable',
            'measurements' => 'array:20,30,50,70,100',
            'measurements.*.q_value' => 'numeric|min:0|nullable',
            'measurements.*.started_at' => 'date_format:Y-m-d\TH:i|before:measurements.*.ended_at|nullable',
            'measurements.*.ended_at' => 'date_format:Y-m-d\TH:i|after:measurements.*.started_at|nullable',
            'measurements.100.started_at' => 'required|date_format:Y-m-d\TH:i|before:measurements.*.ended_at',
            'measurements.100.ended_at' => 'required|date_format:Y-m-d\TH:i|after:measurements.*.started_at',
            'measurements.*.measurement_transformer_reading_start' => 'numeric|min:0|nullable',
            'measurements.*.measurement_transformer_reading_end' => 'numeric|min:0|nullable',
            'measurements.*.measurement_transformer_reading_sum' => 'numeric|min:0|nullable',
            'measurements.100.measurement_transformer_reading_sum' => 'required|numeric|min:0',
            'measurements.*.pcs_reading_start' => 'numeric|min:0|nullable',
            'measurements.*.pcs_reading_end' => 'numeric|min:0|nullable',
            'measurements.*.pcs_reading_sum' => 'numeric|min:0|nullable',
            'measurements.*.comparison_measurement_start' => 'numeric|min:0|nullable',
            'measurements.*.comparison_measurement_end' => 'numeric|min:0|nullable',
            'measurements.*.comparison_measurement_sum' => 'numeric|min:0|nullable',
            'measurements.100.comparison_measurement_sum' => 'required|numeric|min:0',
            'measurements.*.measurement_difference' => 'numeric|min:0|nullable',
            'measurements.100.measurement_difference' => 'required|numeric|min:0',
            'measurements.*.q_value_average_mobile' => 'numeric|min:0|nullable',
            'measurement_difference_up_to_30_q_max' => 'required|numeric|min:0',
            'measurement_difference_above_30_q_max' => 'required|numeric|min:0',
            'reading_difference_up_to_30_q_max' => 'required|numeric|min:0',
            'reading_difference_above_30_q_max' => 'required|numeric|min:0',
            'equipment_in_tolerance_range' => 'required|boolean',
            'equipment_deficiencies' => 'nullable',
            'further_inspection_required' => 'boolean|nullable',
            'comment' => 'nullable',
            'appendix_description' => 'nullable',
            'appendix' => 'mimes:pdf',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
        ];

        if ($speed_measurement_type === 'other') {
            $rules['speed_measurement_type_other'] = 'required';
        } else {
            $rules['speed_measurement_type_other'] = 'prohibited|nullable';
        }

        if ($measurement_transformer_level_unit === 'interface') {
            $rules['measurement_transformer_minimum_level'] = 'prohibited|nullable';
            $rules['measurement_transformer_maximum_level'] = 'prohibited|nullable';
        } else {
            $rules['measurement_transformer_minimum_level'] = 'required|numeric|min:0|lt:measurement_transformer_maximum_level';
            $rules['measurement_transformer_maximum_level'] = 'required|numeric|min:0|gt:measurement_transformer_minimum_level';
        }

        if($measurement_section_slope) {
            $rules['measurement_section_slope_assessment_type'] = 'required';
        } else {
            $rules['measurement_section_slope_assessment_type'] = 'prohibited|nullable';
        }

        if($measurement_section_pipe_visible_inspection_inside_possible === "0") {
            $rules['measurement_section_pipe_visible_inspection_inside'] = 'required';
        } else {
            $rules['measurement_section_pipe_visible_inspection_inside'] = 'prohibited|nullable';
        }

        if($tailwater_runout_section_slope) {
            $rules['tailwater_runout_section_slope_assessment_type'] = 'required';
        } else {
            $rules['tailwater_runout_section_slope_assessment_type'] = 'prohibited|nullable';
        }

        if($tailwater_flow_conditions_influenced) {
            $rules['tailwater_flow_conditions_influencer'] = 'required';
        } else {
            $rules['tailwater_flow_conditions_influencer'] = 'prohibited|nullable';
        }

        if ($comparison_measurements_process === 'mobile_measurement_equipment') {
            $comparison_measurement_mobile_type = $this->input('speed_measurement_type');

            $rules['comparison_measurement_mobile_type'] = 'required|in:doppler_ultrasonic,ultrasonic_signal_transmit_time,ultrasonic_cross_correlation,radar,other';
            if($comparison_measurement_mobile_type === 'other') {
                $rules['comparison_measurement_mobile_type_other'] = 'required';
            } else {
                $rules['comparison_measurement_mobile_type_other'] = 'prohibited|nullable';
            }
            $rules['comparison_measurement_mobile_installation_point'] = 'required';
            $rules['comparison_measurement_mobile_equipment_make'] = 'required';
            $rules['comparison_measurement_mobile_equipment_type'] = 'required';
            $rules['comparison_measurement_mobile_equipment_identifier'] = 'required';
            $rules['comparison_measurement_mobile_equipment_maximum_speed'] = 'required_with:comparison_measurement_mobile_equipment_maximum_speed_unit,comparison_measurement_mobile_equipment_maximum_flow_rate,comparison_measurement_mobile_equipment_maximum_flow_rate_unit|numeric|min:0|nullable';
            $rules['comparison_measurement_mobile_equipment_maximum_speed_unit'] = 'required_with:comparison_measurement_mobile_equipment_maximum_speed,comparison_measurement_mobile_equipment_maximum_flow_rate,comparison_measurement_mobile_equipment_maximum_flow_rate_unit|in:m_s|nullable';
            $rules['comparison_measurement_mobile_equipment_maximum_flow_rate'] = 'required_with:comparison_measurement_mobile_equipment_maximum_speed,comparison_measurement_mobile_equipment_maximum_speed_unit,comparison_measurement_mobile_equipment_maximum_flow_rate_unit|numeric|min:0|nullable';
            $rules['comparison_measurement_mobile_equipment_maximum_flow_rate_unit'] = 'required_with:comparison_measurement_mobile_equipment_maximum_speed,comparison_measurement_mobile_equipment_maximum_speed_unit,comparison_measurement_mobile_equipment_maximum_flow_rate|in:l_s,m3_h|nullable';
            $rules['comparison_measurement_mobile_equipment_q_min'] = '|numeric|min:0|nullable';
            $rules['comparison_measurement_mobile_equipment_last_calibrated_on'] = 'required|date';
            $rules['comparison_measurement_mobile_equipment_last_cal_provider'] = 'required';
            $rules['comparison_measurement_mobile_equipment_last_cal_doc_identifier'] = 'required';
        } else {
            $rules['comparison_measurement_volumetric_basin'] = 'required';
            $rules['comparison_measurement_volumetric_basin_cross_section_area'] = 'required';
            $rules['comparison_measurement_volumetric_height_measurement_equipment'] = 'required';
        }

        return $rules;
    }
}
