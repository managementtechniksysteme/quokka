<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'person_id' => 'required|exists:people,id|unique:employees,person_id',
            'entered_on' => 'required|date',
            'left_on' => 'date|nullable',
            'holidays' => 'required|integer',
            'username' => 'unique:users,username|required_with:password,avatar_colour|nullable',
            'password' => 'confirmed|required_with:username,avatar_colour|nullable',
            'avatar_colour' => 'required_with:username,password|nullable',
        ];
    }
}
