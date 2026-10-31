<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates a quick edit of exactly one field on a report's detail page. Which of
 * the fields a given report type accepts (`date` is that report's own date column) is decided by its controller.
 */
class ReportQuickUpdateRequest extends FormRequest
{
    public const FIELDS = ['weather', 'involved_ids', 'present_ids', 'date'];

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'weather' => 'sometimes|in:sunny,cloudy,rainy,snowy',
            'involved_ids' => 'sometimes|array|min:1',
            'involved_ids.*' => 'exists:employees,person_id',
            'present_ids' => 'sometimes|array',
            'present_ids.*' => 'exists:people,id',
            'date' => 'sometimes|date',
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
