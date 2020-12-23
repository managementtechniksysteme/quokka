<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceReportUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'comment' => 'required',
            'services' => 'required|array|min:1',
            'services.*.provided_on' => 'required|date|distinct',
            'services.*.hours' => 'required|numeric|min:0|multiple_of:0.5',
            'services.*.allowances' => 'required|numeric|min:0|multiple_of:0.5',
            'services.*.kilometres' => 'required|integer|min:0',

        ];
    }
}
