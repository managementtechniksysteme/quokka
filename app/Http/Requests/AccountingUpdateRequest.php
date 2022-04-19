<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountingUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_provided_on' => 'required|date',
            'service_provided_started_at' => 'date_format:H:i|required_with:service_provided_ended_at|nullable',
            'service_provided_ended_at' => 'date_format:H:i|required_with:service_provided_started_at|after:service_provided_started_at|nullable',
            'project_id' => 'required|exists:projects,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0.5|multiple_of:0.5',
            'comment' => 'nullable',
        ];
    }
}
