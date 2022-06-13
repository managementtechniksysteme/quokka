<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProjectDownloadRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'start' => 'date|nullable',
            'end' => 'date|nullable',
            'employee_ids' => 'array|nullable',
            'employee_ids.*' => 'exists:employees,person_id',
            'service_ids' => 'array|nullable',
            'service_ids.*' => 'exists:services,id',
        ];

        if($this->filled('start') && $this->filled('end')) {
            $rules['start'] = $rules['start'] . '|before_or_equal:end';
            $rules['end'] = $rules['end'] . '|after_or_equal:start';
        }

        return $rules;
    }
}
