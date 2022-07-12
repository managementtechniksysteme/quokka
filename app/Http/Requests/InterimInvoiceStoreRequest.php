<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InterimInvoiceStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'billed_on' => 'required|date',
            'amount' => 'required|numeric|min:0|multiple_of:0.01',
            'comment' => 'nullable',
        ];
    }
}
