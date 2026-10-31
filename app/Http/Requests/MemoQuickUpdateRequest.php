<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates a quick edit of exactly one participant list or date on a memo's detail page.
 */
class MemoQuickUpdateRequest extends FormRequest
{
    public const FIELDS = ['present_ids', 'notified_ids', 'meeting_held_on', 'next_meeting_on'];

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'present_ids' => 'sometimes|array',
            'present_ids.*' => 'exists:people,id',
            'notified_ids' => 'sometimes|array',
            'notified_ids.*' => 'exists:people,id',
            'meeting_held_on' => 'sometimes|date',
            'next_meeting_on' => 'sometimes|nullable|date',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (count($this->all()) !== 1 || count(array_intersect(array_keys($this->all()), self::FIELDS)) !== 1) {
                    $validator->errors()->add('field', 'Es muss genau ein Feld übergeben werden.');

                    return;
                }

                if ($validator->errors()->isEmpty()) {
                    $this->validateDatesAgainstMemo($validator);
                }
            },
        ];
    }

    /**
     * The next meeting has to stay after the meeting itself, as in the full form.
     */
    private function validateDatesAgainstMemo(Validator $validator): void
    {
        $memo = $this->route('memo');

        if ($this->has('meeting_held_on') && $memo->next_meeting_on && ! $this->date('meeting_held_on')->lt($memo->next_meeting_on)) {
            $validator->errors()->add('meeting_held_on', 'Das Datum muss vor dem nächsten Termin liegen.');
        }

        if ($this->filled('next_meeting_on') && ! $this->date('next_meeting_on')->gt($memo->meeting_held_on)) {
            $validator->errors()->add('next_meeting_on', 'Der nächste Termin muss nach dem Datum liegen.');
        }
    }
}
