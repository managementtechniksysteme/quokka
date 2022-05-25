<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InspectionReportStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $inspected_on = $this->input('inspected_on');
        $project_id = $this->input('project_id');
        $equipment_identifier = $this->input('equipment_identifier');

        return [
            'project_id' => 'required|exists:projects,id',
            'inspected_on' => [
                'required',
                'date',
                Rule::unique('inspection_reports')
                    ->where(function ($query) use ($inspected_on, $equipment_identifier, $project_id) {
                    return $query
                        ->where('inspected_on', $inspected_on)
                        ->where('equipment_identifier', $equipment_identifier)
                        ->where('project_id', $project_id);
                }),
            ],
            'weather' => 'required|in:sunny,cloudy,rainy,snowy',
            'equipment_type' => 'required',
            'equipment_identifier' => 'required',
            'uvc_lamp_type' => 'required',
            'uvc_lamp_quantity' => 'required|integer|min:1',
            'uvc_lamp_operating_hours' => 'required|integer|min:0',
            'uvc_lamp_impulses' => 'required|integer|min:0',
            'uvc_lamp_uv_intensity_arrival' => 'required|numeric|min:0',
            'uvc_lamp_uv_intensity_departure' => 'required|numeric|min:0',
            'uvc_lamp_values_unit' => 'required|in:percent,W_m2',
            'uvc_lamp_replacement_available' => 'boolean|nullable',
            'uvc_sensor_type' => 'required',
            'uvc_sensor_identifier' => 'required',
            'uvc_sensor_pre_alarm' => 'required|numeric|min:0',
            'uvc_sensor_cut_off_point' => 'required|numeric|min:0',
            'uvc_sensor_values_unit' => 'required|in:%,W_m2',
            'quartz_tube_contaminated' => 'boolean|nullable',
            'quartz_tube_leaking' => 'boolean|nullable',
            'water_suspended_load_visible' => 'boolean|nullable',
            'water_air_bubble_free' => 'boolean|nullable',
            'water_flow_rate' => 'required|numeric|min:0',
            'water_minimum_uv_transmission' => 'required|numeric|min:0|max:100',
            'water_measured_uv_transmission' => 'required|numeric|min:0|max:100',
            'comment' => 'required',
        ];
    }
}
