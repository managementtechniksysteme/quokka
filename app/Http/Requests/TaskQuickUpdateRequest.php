<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Validates a quick edit of exactly one task field from the detail page.
 */
class TaskQuickUpdateRequest extends FormRequest
{
    public const FIELDS = ['status', 'priority', 'billed', 'private', 'employee_id', 'involved_ids'];

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'status' => 'sometimes|in:new,in progress,finished',
            'priority' => 'sometimes|in:low,medium,high',
            'billed' => 'sometimes|in:yes,no,warranty',
            'private' => 'sometimes|boolean',
            'employee_id' => 'sometimes|exists:employees,person_id',
            'involved_ids' => 'sometimes|array',
            'involved_ids.*' => 'exists:employees,person_id',
        ];

        if (Auth::user()->can('tasks.create') && Auth::user()->cannot('tasks.create.private')) {
            $rules['private'] .= '|in:0';
        }

        if (Auth::user()->can('tasks.create.private') && Auth::user()->cannot('tasks.create')) {
            $rules['private'] .= '|in:1';
        }

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $submittedFields = array_intersect(array_keys($this->all()), self::FIELDS);

                if (count($submittedFields) !== 1 || count($this->all()) !== 1) {
                    $validator->errors()->add('field', 'Es muss genau ein Feld übergeben werden.');
                }
            },
        ];
    }
}
