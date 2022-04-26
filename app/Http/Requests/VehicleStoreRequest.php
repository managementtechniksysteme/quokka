<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'make' => 'required',
            'model' => 'required',
            'registration_identifier' => 'required|unique:vehicles',
            'comment' => 'nullable',
        ];
    }
}
