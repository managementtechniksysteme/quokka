<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $task = $this->task;
        $name = $request->name;
        $project_id = $request->project_id;

        return [
            'name' => [
                'required',
                Rule::unique('tasks')->where(function ($query) use ($name, $project_id) {
                    return $query->where('name', $name)->where('project_id', $project_id);
                })->ignore($task),
            ],
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
        ];
    }
}
