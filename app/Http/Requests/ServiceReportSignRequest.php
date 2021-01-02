<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ServiceReportSignRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        // TODO: proper base64 image validation?
        return [
            'signature' => 'required',
            'send_download_request' => 'accepted|sometimes',
        ];
    }
}
