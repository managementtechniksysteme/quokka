<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'make' => 'required',
            'model' => 'required',
            'registration_identifier' => 'required|unique:vehicles,registration_identifier,'.$this->vehicle->id,
            'private' => 'required|boolean',
            'comment' => 'nullable',
        ];
    }
}
