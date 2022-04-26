<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driven_on' => 'required|date',
            'start_kilometres' => 'required|date',
            'end_kilometres' => 'required|date',
            'driven_kilometres' => 'required|date',
            'project_id' => 'sometimes|exists:projects,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'comment' => 'nullable',
        ];
    }
}
