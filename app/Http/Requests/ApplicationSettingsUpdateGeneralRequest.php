<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ApplicationSettingsUpdateGeneralRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'allowances_service_id' => 'exists:App\Models\WageService,id|different:overtime_50_service_id|different:overtime_100_service_id|different:time_balance_service_id|different:holiday_service_id|nullable',
            'overtime_50_service_id' => 'exists:App\Models\WageService,id|different:allowances_service_id|different:overtime_100_service_id|different:time_balance_service_id|different:holiday_service_id|nullable',
            'overtime_100_service_id' => 'exists:App\Models\WageService,id|different:allowances_service_id|different:overtime_50_service_id|different:time_balance_service_id|different:holiday_service_id|nullable',
            'time_balance_service_id' => 'exists:App\Models\WageService,id|different:allowances_service_id|different:overtime_50_service_id|different:overtime_100_service_id|different:holiday_service_id|nullable',
            'holiday_service_id' => 'required_with:holiday_yearly_allowance|different:allowances_service_id|different:overtime_50_service_id|different:overtime_100_service_id|different:time_balance_service_id|exists:App\Models\WageService,id',
            'holiday_yearly_allowance' => 'integer|min:1|nullable',
            'currency_unit' => 'required',
            'services_hour_unit' => 'exists:services,unit|nullable',
            'accounting_min_amount' => 'required|numeric|min:0',
            'project_material_costs_warning_percentage' => 'integer|min:1|max:99|nullable',
            'project_wage_costs_warning_percentage' => 'integer|min:1|max:99|nullable',
            'project_overall_costs_warning_percentage' => 'integer|min:1|max:99|nullable',
            'signature_notify_user_id' => 'exists:users,employee_id|nullable',
            'task_due_soon_days' => 'required|integer|min:1',
        ];
    }
}
