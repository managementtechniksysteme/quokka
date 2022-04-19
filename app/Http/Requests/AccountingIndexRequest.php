<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountingIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start' => 'date|nullable',
            'end' => 'date|nullable',
            'project_id' => 'exists:projects,id|nullable',
            'service_id' => 'exists:services,id|nullable',
            'only_own' => 'accepted|sometimes',
        ];
    }
}
