<?php

namespace App\Http\Requests;

use App\Models\MaterialService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialServiceUpdateRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'name' => [
                'required',
                Rule::unique(MaterialService::class)->ignore($this->material_service),
            ],
            'description' => 'required',
        ];
    }
}
