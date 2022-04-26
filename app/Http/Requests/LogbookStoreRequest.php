<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driven_on' => 'required|date',
            'start_kilometres' => 'required|integer|min:1|',
            'end_kilometres' => 'required|integer|min:1|',
            'driven_kilometres' => 'required|integer|min:1',
            'origin' => 'required',
            'destination' => 'required',
            'project_id' => 'sometimes|exists:projects,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'comment' => 'nullable',
        ];
    }
}
