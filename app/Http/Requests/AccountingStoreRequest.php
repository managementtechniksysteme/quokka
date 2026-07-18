<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AccountingStoreRequest extends FormRequest
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

                // service_provided_started_at/ended_at might be missing or malformed at this
                // point - the date_format:H:i rules above already reject that cleanly, so the
                // lte bound is only computable (and only needed) once both are valid times.
                if($this->filled('service_provided_started_at') && $this->filled('service_provided_ended_at')) {
                    try {
                        $start = Carbon::createFromTimeString($this->input('service_provided_started_at'));
                        $end = Carbon::createFromTimeString($this->input('service_provided_ended_at'));

                        $minAmountMinutes = $minAmount * 60;
                        $differenceMinutes = $start->floatDiffInMinutes($end);
                        $maxDifferenceMinutes = $differenceMinutes - ($differenceMinutes % $minAmountMinutes);
                        $maxHours = $maxDifferenceMinutes / 60;

                        $rules['amount'] = $rules['amount']."|lte:{$maxHours}";
                    } catch (\Exception $exception) {
                        Log::info('AccountingStoreRequest could not parse service_provided_started_at/ended_at: '.$exception->getMessage());
                    }
                }
            }
            else {
                $rules['service_provided_started_at'] = 'prohibited|nullable';
                $rules['service_provided_ended_at'] = 'prohibited|nullable';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'amount.lte' => ':attribute darf den angegebenen Zeitbereich in Stunden (:value) nicht überschreiten.'
        ];
    }
}
