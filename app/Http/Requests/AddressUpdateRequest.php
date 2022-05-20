<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $address = $this->address;
        $street_number = $this->input('street_number');
        $postcode = $this->input('postcode');
        $city = $this->input('city');

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
            'city' => 'required|max:50',
        ];
    }
}
