<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\WageService;

class WageServiceUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique(WageService::class)->ignore($this->wage_service),
            ],
            'description' => 'required',
            'unit' => 'required',
            'costs' => 'numeric|min:0|multiple_of:0.1|nullable',
        ];
    }
}
