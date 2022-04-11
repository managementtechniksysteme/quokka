<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WageServiceStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|unique:App\Models\WageService',
            'description' => 'required',
            'unit' => 'required',
            'costs' => 'numeric|min:0|multiple_of:0.1|nullable',
        ];
    }
}
