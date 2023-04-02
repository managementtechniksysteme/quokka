<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceGroupCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project' =>
                'sometimes',
                Rule::exists('projects')
                    ->where('include_in_finances', false)
                    ->where(function ($query) {
                        $query->doesntHave('financeGroup');
                    }),
        ];
    }
}
