<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:companies',
            'name_2' => 'nullable',
            'phone' => 'nullable',
            'fax' => 'nullable',
            'email' => 'email|nullable',
            'website' => 'url|nullable',
            'address_id' => 'exists:addresses,id|nullable',
            'street_number' => 'required_with:postcode,city|nullable',
            'postcode' => 'required_with:street_number,city|digits_between:4,5|nullable',
            'city' => 'required_with:street_number,postcode|nullable',
            'comment' => 'nullable',
        ];
    }
}
