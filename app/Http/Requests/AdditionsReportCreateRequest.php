<?php

namespace App\Http\Requests;

use App\Models\ApplicationSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdditionsReportCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'project' => 'sometimes|exists:projects,id'
        ];
    }
}
