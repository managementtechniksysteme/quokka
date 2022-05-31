<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdditionsReportUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $additions_report = $this->additions_report;
        $services_provided_on = $this->input('services_provided_on');
        $project_id = $this->input('project_id');

        $minAmount = ApplicationSettings::get()->accounting_min_amount;

        return [
            'services_provided_on' => [
                'required',
                'date',
                Rule::unique('additions_reports')->where(function ($query) use ($services_provided_on, $project_id) {
                    return $query->where('services_provided_on', $services_provided_on)->where('project_id', $project_id);
                })->ignore($additions_report),
            ],
            'hours' => "required|numeric|min:$minAmount|multiple_of:$minAmount",
            'inspection_comment' => 'nullable',
            'missing_documents' => 'nullable',
            'special_occurrences' => 'nullable',
            'imminent_danger' => 'nullable',
            'concerns' => 'nullable',
            'weather' => 'required|in:sunny,cloudy,rainy,snowy',
            'minimum_temperature' => 'required|integer|lte:maximum_temperature',
            'maximum_temperature' => 'required|integer|gte:minimum_temperature',
            'project_id' => 'required|exists:projects,id',
            'involved_ids' => 'required|array|min:1',
            'involved_ids.*' => 'exists:employees,person_id',
            'present_ids' => 'array|nullable',
            'present_ids.*' => 'exists:people,id',
            'other_visitors' => 'nullable',
            'comment' => 'required',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
        ];
    }
}
