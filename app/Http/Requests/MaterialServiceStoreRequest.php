<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialServiceStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|unique:App\Models\MaterialService',
            'description' => 'required',
        ];
    }
}
