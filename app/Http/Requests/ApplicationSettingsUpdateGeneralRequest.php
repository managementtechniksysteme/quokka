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
            'signature_notify_user_id' => 'exists:users,employee_id|nullable',
            'task_due_soon_days' => 'required|integer|min:1',
        ];
    }
}
