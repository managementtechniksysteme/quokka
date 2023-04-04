<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    public function messages()
    {
        return [
            'name.unique' => 'Für diese Firma existiert bereits ein Projekt mit diesem Namen',
            'include_in_finances.prohibited_if' => 'Das Feld darf nicht zusammen mit aktuellen Kosten verwendet werden',
            'financial_costs.prohibited_if' => 'Das Feld darf nicht zusammen mit In Finanzen enthalten verwendet werden',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $project = $this->project;
        $name = $this->input('name');
        $company_id = $this->input('company_id');

        return [
            'name' => [
                'required',
                Rule::unique('projects')->where(function ($query) use ($name, $company_id) {
                    return $query->where('name', $name)->where('company_id', $company_id);
                })->ignore($project),
            ],
            'starts_on' => 'nullable',
            'is_pre_execution' => 'boolean',
            'include_in_finances' => 'boolean',
            'ends_on' => 'after_or_equal:starts_on|before_or_equal:today|nullable',
            'material_costs' => 'numeric|min:0|multiple_of:0.01|nullable',
            'wage_costs' => 'numeric|min:0|multiple_of:0.01|nullable',
            'financial_costs' => 'numeric|min:0|multiple_of:0.01|required_if:include_in_finances,false|prohibited_if:include_in_finances,true|nullable',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable',
        ];
    }
}
