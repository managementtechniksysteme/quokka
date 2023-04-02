<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceGroupStoreRequest extends FormRequest
{
    public function messages()
    {
        return [
            'title.prohibits' => 'Titel muss leer sein wenn ein Projekt angegeben ist.',
            'project_id.prohibits' => 'Projekt muss leer sein wenn ein Titel angegeben ist.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|prohibits:project_id',
            'project_id' =>
                'sometimes',
                Rule::exists('projects')
                    ->where('include_in_finances', false)
                    ->where(function ($query) {
                        $query->doesntHave('financeGroup');
                    }),
                'prohibits:title',
            'comment' => 'nullable',
        ];
    }
}
