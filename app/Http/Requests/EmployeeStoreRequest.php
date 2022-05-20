<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $minAmount = ApplicationSettings::get()->accounting_min_amount;

        return [
            'person_id' => 'required|exists:people,id|unique:employees,person_id',
            'entered_on' => 'required|date',
            'left_on' => 'date|nullable',
            'holidays' => "required|numeric|multiple_of:$minAmount",
            'username' => 'unique:users,username|required_with:password,avatar_colour|nullable',
            'password' => 'confirmed|required_with:username,avatar_colour|nullable',
            'avatar_colour' => 'required_with:username,password|nullable',
        ];
    }
}
