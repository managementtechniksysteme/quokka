<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'required|unique:companies',
            'name_2' => 'nullable',
            'phone' => 'nullable',
            'fax' => 'nullable',
            'email' => 'email|nullable',
            'website' => 'url|nullable',
            'address_id' => 'exists:addresses,id|nullable',
            'address_name' => 'required_with:street_number,postcode,city|nullable',
            'street_number' => 'required_with:address_name,postcode,city|nullable',
            'postcode' => 'required_with:address_name,street_number,city|digits_between:4,5|nullable',
            'city' => 'required_with:address_name,street_number,postcode|nullable',
            'operator_address_id' => 'exists:addresses,id|nullable',
            'operator_address_name' => 'required_with:operator_street_number,operator_postcode,operator_city|nullable',
            'operator_street_number' => 'required_with:operator_address_name,operator_postcode,operator_city|nullable',
            'operator_postcode' => 'required_with:operator_address_name,operator_street_number,operator_city|digits_between:4,5|nullable',
            'operator_city' => 'required_with:operator_address_name,operator_street_number,operator_postcode|nullable',
            'contact_person_id' => 'exists:people,id|nullable',
            'comment' => 'nullable',
        ];
    }
}
