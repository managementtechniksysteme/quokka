<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSettingsUpdateNotificationTargetsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'array|nullable',
            'email.*' => 'exists:notification_types,id',
            'webpush' => 'array|nullable',
            'webpush.*' => 'exists:notification_types,id',
        ];
    }
}
