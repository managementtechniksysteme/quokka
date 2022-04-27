<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driven_on' => 'required|date',
            'start_kilometres' => 'required|integer|min:0|lt:end_kilometres',
            'end_kilometres' => 'required|integer|min:1|gt:start_kilometres',
            'driven_kilometres' => 'required|integer|min:1',
            'litres_refuelled' => 'integer|min:1|nullable',
            'origin' => 'required',
            'destination' => 'required',
            'project_id' => 'exists:projects,id|nullable',
            'vehicle_id' => 'exists:vehicles,id|nullable',
            'comment' => 'nullable',
        ];
    }
}
