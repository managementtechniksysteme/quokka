<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaskStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'starts_on' => 'date|required_with:ends_on|nullable',
            'ends_on' => 'date|after_or_equal:starts_on|nullable',
            'due_on' => 'date|nullable',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:new,in progress,finished',
            'billed' => 'required|in:yes,no,warranty',
            'private' => 'required|boolean',
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,person_id',
            'involved_ids' => 'array|nullable',
            'involved_ids.*' => 'exists:employees,person_id',
            'comment' => 'nullable',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
        ];

        if(Auth::user()->can('tasks.create') && Auth::user()->cannot('tasks.create.private')) {
            $rules['private'] = $rules['private'].'|in:0';
        }

        if(Auth::user()->can('tasks.create.private') && Auth::user()->cannot('tasks.create')) {
            $rules['private'] = $rules['private'].'|in:1';
        }

        return $rules;
    }
}
