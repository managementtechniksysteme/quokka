<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
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
            'street_number' => [
                'required_with:postcode,city',
                'nullable',
                Rule::unique('addresses')->where(function ($query) use ($street_number, $postcode, $city) {
                    return $query
                        ->where('street_number', $street_number)
                        ->where('postcode', $postcode)
                        ->where('city', $city);
                }),
            ],
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
