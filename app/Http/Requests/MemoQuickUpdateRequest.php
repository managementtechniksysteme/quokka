<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates a quick edit of exactly one participant list on a memo's detail page.
 */
class MemoQuickUpdateRequest extends FormRequest
{
    public const FIELDS = ['present_ids', 'notified_ids'];

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
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (count($this->all()) !== 1 || count(array_intersect(array_keys($this->all()), self::FIELDS)) !== 1) {
                    $validator->errors()->add('field', 'Es muss genau ein Feld übergeben werden.');
                }
            },
        ];
    }
}
