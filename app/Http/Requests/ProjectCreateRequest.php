<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProjectCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'company' => 'sometimes|exists:projects,id',
        ];
    }
}
