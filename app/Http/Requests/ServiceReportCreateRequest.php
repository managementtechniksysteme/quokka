<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use App\Models\WageService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServiceReportCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $hourBasedServiceIds = WageService::whereUnit(ApplicationSettings::get()->services_hour_unit)
            ->pluck('id')->toArray();

        return [
            'accounting' => 'sometimes|array|prohibits:logbook,project',
            'accounting.*' => [
                Rule::exists('accounting', 'id')->where(function ($query) use ($hourBasedServiceIds) {
                    return $query
                        ->where('employee_id', Auth::id())
                        ->whereIn('service_id', $hourBasedServiceIds);
                }),
            ],
            'logbook' => 'sometimes|array|prohibits:accounting,project',
            'logbook.*' => [
                Rule::exists('logbook', 'id')->where(function ($query) {
                    return $query->where('employee_id', Auth::id());
                }),
            ],
            'project' => 'sometimes|exists:projects.id|prohibits:accounting,logbook'
        ];
    }
}
