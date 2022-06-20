<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectStoreRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.unique' => 'Für diese Firma existiert bereits ein Projekt mit diesem Namen',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $name = $this->input('name');
        $company_id = $this->input('company_id');

        return [
            'name' => [
                'required',
                Rule::unique('projects')->where(function ($query) use ($name, $company_id) {
                    return $query->where('name', $name)->where('company_id', $company_id);
                }),
            ],
            'starts_on' => 'date|nullable',
            'ends_on' => 'date|after_or_equal:starts_on|nullable',
            'material_costs' => 'numeric|min:0|multiple_of:0.01|nullable',
            'wage_costs' => 'numeric|min:0|multiple_of:0.01|nullable',
            'billed_costs' => 'numeric|min:0|multiple_of:0.01|nullable',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable',
        ];
    }
}
