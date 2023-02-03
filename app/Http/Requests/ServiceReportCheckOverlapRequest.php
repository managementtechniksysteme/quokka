<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceReportCheckOverlapRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'report_id' => 'sometimes|exists:service_reports,id',
            'project_id' => 'required|exists:projects,id',
            'dates' => 'required|array',
            'dates.*' => 'date',
        ];
    }
}
