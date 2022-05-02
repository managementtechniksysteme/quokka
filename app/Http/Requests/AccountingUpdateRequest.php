<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class AccountingUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $minAmount = ApplicationSettings::get()->accounting_min_amount;

        $rules = [
            'service_provided_on' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric',
            'comment' => 'nullable',
        ];

        if($this->input('service_id') !== null) {
            $service = Service::find($this->input('service_id'));

            if($service && $service->type === 'material') {
                $rules['amount'] = $rules['amount']."|min:0|multiple_of:0.01";
            }
            elseif($service && $service->type === 'wage') {
                $rules['amount'] = $rules['amount']."|min:$minAmount|multiple_of:$minAmount";
            }

            if($service && $service->type === 'wage' &&
                $service->unit === ApplicationSettings::get()->services_hour_unit) {
                $rules['service_provided_started_at'] = 'required|date_format:H:i|before:service_provided_ended_at';
                $rules['service_provided_ended_at'] = 'required|date_format:H:i|after:service_provided_started_at';
            }
            else {
                $rules['service_provided_started_at'] = 'prohibited|nullable';
                $rules['service_provided_ended_at'] = 'prohibited|nullable';
            }
        }

        return $rules;
    }
}
