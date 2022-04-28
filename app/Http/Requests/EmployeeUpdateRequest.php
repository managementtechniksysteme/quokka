<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $minAmount = ApplicationSettings::get()->accounting_min_amount;

        $employee = $this->employee;
        $employee->load('user');

        return [
            'person_id' => [
                'required',
                'exists:people,id',
                Rule::unique('employees', 'person_id')->ignore($employee),
            ],
            'entered_on' => 'required|date',
            'left_on' => 'date|nullable',
            'holidays' => "required|numeric|multiple_of:$minAmount",
            'username' => [
                Rule::unique('users', 'username')->ignore($employee->user),
                'required_with:password,avatar_colour',
                'nullable',
            ],
            'password' => 'confirmed|nullable'.$employee->user ? '' : '|required_with:password,avatar_colour',
            'avatar_colour' => 'required_with:username,password|nullable',
        ];
    }
}
