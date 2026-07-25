<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'driven_on' => 'required|date',
            'start_kilometres' => 'required|integer|min:0|lt:end_kilometres',
            'end_kilometres' => 'required|integer|min:1|gt:start_kilometres',
            'driven_kilometres' => 'required|integer|min:1',
            'litres_refuelled' => 'integer|min:1|nullable',
            'origin' => 'required',
            'destination' => 'required',
            'project_id' => 'exists:projects,id|nullable',
            'vehicle_id' => 'exists:vehicles,id',
            'comment' => 'nullable',
        ];

        $start = $this->input('start_kilometres');
        $end = $this->input('end_kilometres');
        $driven = $this->input('driven_kilometres');

        // Each field's size: bound is derived from the other two, so it's only
        // computable (and only meaningful) once those two are actually numeric -
        // the `integer` rule above already rejects anything else on its own.
        if(is_numeric($end) && is_numeric($driven)) {
            $rules['start_kilometres'] .= '|size:'.($end - $driven);
        }

        if(is_numeric($start) && is_numeric($driven)) {
            $rules['end_kilometres'] .= '|size:'.($start + $driven);
        }

        if(is_numeric($end) && is_numeric($start)) {
            $rules['driven_kilometres'] .= '|size:'.($end - $start);
        }

        return $rules;
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
