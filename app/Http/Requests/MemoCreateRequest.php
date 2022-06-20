<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemoCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'template' => 'sometimes|exists:memos,id|prohibits:project|prohibits:project',
            'project' => 'sometimes|exists:projects,id|prohibits:template|prohibits:template'
        ];
    }
}
