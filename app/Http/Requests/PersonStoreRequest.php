<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'title_prefix' => 'nullable',
            'title_suffix' => 'nullable',
            'gender' => 'required|in:male,female,neutral',
            'address_id' => 'exists:addresses,id|nullable',
            'street_number' => 'required_with:postcode,city|nullable',
            'postcode' => 'required_with:street_number,city|digits_between:4,5|nullable',
            'city' => 'required_with:street_number,postcode|nullable',
            'company_id' => 'exists:companies,id|nullable',
            'department' => 'nullable',
            'role' => 'nullable',
            'phone_company' => 'nullable',
            'phone_mobile' => 'nullable',
            'phone_private' => 'nullable',
            'fax' => 'nullable',
            'email' => 'email|nullable|unique:people,email',
            'website' => 'url|nullable',
            'comment' => 'nullable',
        ];
    }
}
