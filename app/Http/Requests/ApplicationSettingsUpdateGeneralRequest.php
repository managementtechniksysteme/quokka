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
            'holiday_service_id' => 'required_with:holiday_yearly_allowance|exists:App\Models\WageService,id',
            'holiday_yearly_allowance' => 'integer|min:1|nullable',
            'currency_unit' => 'required',
            'services_hour_unit' => 'exists:services,unit|nullable',
            'accounting_min_amount' => 'required|numeric|min:0',
            'signature_notify_user_id' => 'exists:users,employee_id|nullable',
            'task_due_soon_days' => 'required|integer|min:1',
        ];
    }
}
