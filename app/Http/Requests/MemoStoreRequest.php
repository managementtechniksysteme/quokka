<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemoStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $title = $this->input('title');
        $project_id = $this->input('project_id');

        return [
            'title' => [
                'required',
                Rule::unique('memos')->where(function ($query) use ($title, $project_id) {
                    return $query->where('title', $title)->where('project_id', $project_id);
                }),
            ],
            'meeting_held_on' => 'required|date',
            'next_meeting_on' => 'date|after:meeting_held_on|nullable',
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,person_id',
            'person_id' => 'exists:people,id|nullable',
            'present_ids' => 'array|nullable',
            'present_ids.*' => 'exists:people,id',
            'notified_ids' => 'array|nullable',
            'notified_ids.*' => 'exists:people,id',
            'comment' => 'required',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'mimes:jpeg,bmp,png,gif,svg,pdf',
        ];
    }
}
