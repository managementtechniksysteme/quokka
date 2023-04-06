<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectControllingIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'project' => 'sometimes|required_with:start,end|exists:projects,id',
            'start' => 'date|nullable',
            'end' => 'date|nullable',
        ];

        if($this->filled('start') && $this->filled('end')) {
            $rules['start'] = $rules['start'] . '|before_or_equal:end';
            $rules['end'] = $rules['end'] . '|after_or_equal:start';
        }

        return $rules;
    }
}
