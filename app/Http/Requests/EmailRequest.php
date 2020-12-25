<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'email_to' => 'required|array',
            'email_to.*.email' => 'email',
            'email_cc' => 'array',
            'email_cc.*.email' => 'email',
            'email_bcc' => 'array',
            'email_bcc.*.email' => 'email',
        ];
    }
}
