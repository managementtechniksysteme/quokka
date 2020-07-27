<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PersonUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $address_name = $request->address_name;
        $street_number = $request->street_number;
        $postcode = $request->postcode;
        $city = $request->city;

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'title_prefix' => 'nullable',
            'title_suffix' => 'nullable',
            'gender' => 'required|in:male,female,neutral',
            'address_id' => 'exists:addresses,id|nullable',
            'address_name' => 'required_with:street_number,postcode,city|nullable',
            'street_number' => 'required_with:address_name,postcode,city|nullable',
            'postcode' => 'required_with:address_name,street_number,city|digits_between:4,5|nullable',
            'city' => 'required_with:address_name,street_number,postcode|nullable',
            'company_id' => 'exists:companies,id|nullable',
            'department' => 'nullable',
            'role' => 'nullable',
            'phone_company' => 'nullable',
            'phone_mobile' => 'nullable',
            'phone_private' => 'nullable',
            'fax' => 'nullable',
            'email' => 'email|nullable|unique:people,email,'.$this->person->id,
            'website' => 'url|nullable',
            'comment' => 'nullable',
        ];
    }
}
