<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceGroupUpdateRequest extends FormRequest
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
            'title' => 'required|unique:finance_groups,title,'.$this->finance_group->id,
            'comment' => 'nullable',
        ];
    }
}
