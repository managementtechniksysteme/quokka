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
        return [
            'project' => 'sometimes|required_with:start,end|exists:projects,id',
            'start' => 'date|before_or_equal:end|nullable',
            'end' => 'date|after_or_equal:end|nullable',
        ];
    }
}
