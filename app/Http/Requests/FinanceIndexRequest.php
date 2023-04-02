<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group' => 'sometimes|exists:finance_groups,id',
            'project' => [
                'sometimes',
                Rule::exists('projects', 'id')->where('include_in_finances', true),
            ],
        ];
    }
}
