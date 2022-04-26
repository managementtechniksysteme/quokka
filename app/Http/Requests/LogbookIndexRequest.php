<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogbookIndexRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'start' => 'sometimes|date',
            'end' => 'sometimes|date',
            'project_id' => 'sometimes|exists:projects,id',
            'vehicle_id' => 'sometimes|exists:vehicle,id',
            'only_own' => 'sometimes|accepted',
        ];

        if($this->input('start') !== null && $this->input('end') !== null) {
            $rules['start'] = $rules['start'] . '|before_or_equal:end';
            $rules['end'] = $rules['end'] . '|after_or_equal:start';
        }

        return $rules;
    }
}
