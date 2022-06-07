<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceReportUpdateRequest extends FormRequest
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
            'services.*.service_report_id' => 'nullable|in:'.$this->service_report->id,
            'services.*.provided_on' => 'required|date|distinct',
            'services.*.hours' => 'required|numeric|min:0|multiple_of:0.5',
            'services.*.kilometres' => 'required|integer|min:0',
            'remove_attachments' => 'array|nullable',
            'remove_attachments.*' => 'exists:media,id',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
        ];
    }
}
