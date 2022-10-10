<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlowMeterInspectionReportCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'template' => 'sometimes|exists:flow_meter_inspection_reports,id|prohibits:project',
            'project' => 'sometimes|exists:projects,id|prohibits:template'
        ];
    }
}
