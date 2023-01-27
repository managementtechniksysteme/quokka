<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AccountingDownloadRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'start' => 'sometimes|date',
            'end' => 'sometimes|date',
            'project_id' => 'sometimes|exists:projects,id',
            'service_id' => 'sometimes|exists:services,id',
            'employee_ids' => 'sometimes|array',
            'employee_ids.*' => 'exists:employees,person_id'
        ];

        if($this->filled('start') && $this->filled('end')) {
            $rules['start'] = $rules['start'] . '|before_or_equal:end';
            $rules['end'] = $rules['end'] . '|after_or_equal:start';
        }

        if(Auth::user()->can('accounting.view.own') && Auth::user()->cannot('accounting.view.other')) {
            $rules['employee_ids.*'] = $rules['employee_ids'] . '|in:'.Auth::user()->employee_id;
        }

        if(Auth::user()->can('accounting.view.other') && Auth::user()->cannot('accounting.view.own')) {
            $rules['employee_ids'] = $rules['employee_ids'] . '|not_in:'.Auth::user()->employee_id;
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        abort(Response::HTTP_FORBIDDEN);
    }
}
