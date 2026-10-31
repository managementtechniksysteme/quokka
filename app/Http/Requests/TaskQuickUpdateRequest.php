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
    public const FIELDS = ['status', 'priority', 'billed', 'private', 'employee_id', 'involved_ids', 'due_on', 'starts_on', 'ends_on'];

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
            'due_on' => 'sometimes|nullable|date',
            'starts_on' => 'sometimes|nullable|date',
            'ends_on' => 'sometimes|date',
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

                    return;
                }

                if ($validator->errors()->isEmpty()) {
                    $this->validateDateAgainstTask($validator);
                }
            },
        ];
    }

    /**
     * The same date rules as the full form: the end needs a start and may not precede it, the
     * start can't be removed or moved behind an existing end, and only a finished task has an end.
     */
    private function validateDateAgainstTask(Validator $validator): void
    {
        $task = $this->route('task');

        if ($this->has('ends_on')) {
            if ($task->status !== 'finished') {
                $validator->errors()->add('ends_on', 'Das Ende kann nur bei einer erledigten Aufgabe gesetzt werden.');
            } elseif (! $task->starts_on || $task->starts_on->gt($this->date('ends_on'))) {
                $validator->errors()->add('ends_on', 'Das Ende darf nicht vor dem Start liegen.');
            }
        }

        if ($this->has('starts_on') && $task->ends_on) {
            if ($this->input('starts_on') === null) {
                $validator->errors()->add('starts_on', 'Der Start kann nicht entfernt werden, solange ein Ende gesetzt ist.');
            } elseif ($this->date('starts_on')->gt($task->ends_on)) {
                $validator->errors()->add('starts_on', 'Der Start darf nicht nach dem Ende liegen.');
            }
        }
    }
}
