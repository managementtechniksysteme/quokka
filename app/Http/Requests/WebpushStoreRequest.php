<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebpushStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'endpoint' => 'required',
            'public_key' => 'required',
            'auth_token' => 'required',
        ];
    }
}
