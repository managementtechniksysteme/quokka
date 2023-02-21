<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'template' => 'sometimes|exists:tasks,id|prohibits:project,note',
            'project' => 'sometimes|exists:projects,id|prohibits:template,note',
            'note' => 'sometimes|exists:notes,id|prohibits:template,project',
        ];
    }
}
