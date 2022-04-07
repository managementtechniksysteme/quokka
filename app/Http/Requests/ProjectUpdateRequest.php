<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $project = $this->project;
        $name = $request->name;
        $company_id = $request->company_id;

        return [
            'name' => [
                'required',
                Rule::unique('projects')->where(function ($query) use ($name, $company_id) {
                    return $query->where('name', $name)->where('company_id', $company_id);
                })->ignore($project),
            ],
            'starts_on' => 'nullable',
            'ends_on' => 'after_or_equal:starts_on|nullable',
            'material_costs' => 'numeric|min:0|multiple_of:0.5|nullable',
            'wage_costs' => 'numeric|min:0|multiple_of:0.5|nullable',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable',
        ];
    }
}
