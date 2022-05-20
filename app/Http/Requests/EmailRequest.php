<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email_to' => 'required|array',
            'email_to.*.email' => 'email',
            'email_cc' => 'array',
            'email_cc.*.email' => 'email',
            'email_bcc' => 'array',
            'email_bcc.*.email' => 'email',
            'attachment_ids' => 'array|nullable',
            'attachment_ids.*' => 'exists:media,id',
        ];
    }
}
