<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceReportStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'comment' => 'required',
            'send_signature_request' => 'accepted|sometimes',
            'services' => 'required|array|min:1',
            'services.*.service_report_id' => 'nullable|in:null',
            'services.*.provided_on' => 'required|date|distinct',
            'services.*.hours' => 'required|numeric|min:0|multiple_of:0.5',
            'services.*.kilometres' => 'required|integer|min:0',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
        ];
    }
}
