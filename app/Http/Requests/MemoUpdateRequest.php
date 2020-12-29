<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemoUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $memo = $this->memo;
        $title = $request->title;
        $project_id = $request->project_id;

        return [
            'title' => [
                'required',
                Rule::unique('memos')->where(function ($query) use ($title, $project_id) {
                    return $query->where('title', $title)->where('project_id', $project_id);
                })->ignore($memo),
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
            'remove_attachments' => 'array|nullable',
            'remove_attachments.*' => 'exists:media,id',
            'new_attachments' => 'array|nullable',
            'new_attachments.*.file' => 'image',
        ];
    }
}
