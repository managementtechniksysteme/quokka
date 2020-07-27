<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $address = $this->address;
        $name = $request->name;
        $street_number = $request->street_number;
        $postcode = $request->postcode;
        $city = $request->city;

        return [
            'name' => [
                'required',
                Rule::unique('addresses')
                    ->where('street_number', $street_number)
                    ->where('postcode', $postcode)
                    ->where('city', $city)
                    ->ignore($address),
            ],
            'street_number' => 'required',
            'postcode' => 'required|digits_between:4,5',
            'city' => 'required',
        ];
    }
}
