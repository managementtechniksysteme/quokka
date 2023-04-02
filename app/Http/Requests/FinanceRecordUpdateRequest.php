<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceRecordUpdateRequest extends FormRequest
{
    public function messages()
    {
        return [
            'title.unique' => 'Für diese Finanzgruppe existiert bereits ein Eintrag mit diesem Titel',
        ];
    }

    public function rules(): array
    {
        $title = $this->input('title');
        $financeGroup = $this->finance_group;
        $financeRecord = $this->finance_record;

        return [
            'title' => [
                'required',
                Rule::unique('finance_records')->where(function ($query) use ($title, $financeGroup) {
                    return $query->where('title', $title)->where('finance_group_id', $financeGroup->id);
                })->ignore($financeRecord),
            ],
            'billed_on' => 'required|date',
            'amount' => 'required|numeric|multiple_of:0.01',
            'comment' => 'nullable',
        ];
    }
}
