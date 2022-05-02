<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $required_start_kilometres = $this->input('end_kilometres')-$this->input('driven_kilometres');
        $required_end_kilometres = $this->input('start_kilometres')+$this->input('driven_kilometres');
        $required_driven_kilometres = $this->input('end_kilometres')-$this->input('start_kilometres');

        return [
            'driven_on' => 'required|date',
            'start_kilometres' => "required|integer|min:0|lt:end_kilometres|size:{$required_start_kilometres}",
            'end_kilometres' => "required|integer|min:1|gt:start_kilometres|size:{$required_end_kilometres}",
            'driven_kilometres' => "required|integer|min:1|size:{$required_driven_kilometres}",
            'litres_refuelled' => 'integer|min:1|nullable',
            'origin' => 'required',
            'destination' => 'required',
            'project_id' => 'exists:projects,id|nullable',
            'vehicle_id' => 'exists:vehicles,id',
            'comment' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'start_kilometres.lt' => 'Die Start Kilometer müssen weniger als die Ende Kilometer sein',
            'end_kilometres.gt' => 'Die Ende Kilometer müssen mehr als die Start Kilometer sein',
            'start_kilometres.size' => 'Die Start Kilometer müssen der Differenz aus Ende Kilometer und gefahrenen Kilometern entsprechen',
            'end_kilometres.size' => 'Die Ende Kilometer müssen der Summe aus Start Kilometer und gefahrenen Kilometern entsprechen',
            'driven_kilometres.size' => 'Die gefahrenen Kilometer müssen der Differenz aus Ende Kilometer und Start Kilometer entsprechen'
        ];
    }
}
