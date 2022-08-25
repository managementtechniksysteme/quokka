<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceReportDownloadListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'sometimes|exists:companies,id|prohibits:project_id',
            'project_id' => 'sometimes|exists:projects,id|prohibits:company_id',
        ];
    }
}
