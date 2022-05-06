<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LogbookDownloadRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'start' => 'sometimes|date',
            'end' => 'sometimes|date',
            'project_id' => 'sometimes|exists:projects,id',
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'only_own' => 'sometimes|accepted',
        ];

        if($this->filled('start') && $this->filled('end')) {
            $rules['start'] = $rules['start'] . '|before_or_equal:end';
            $rules['end'] = $rules['end'] . '|after_or_equal:start';
        }

        if(Auth::user()->can('logbook.view.own') && Auth::user()->cannot('logbook.view.other')) {
            $rules['only_own'] = 'required|accepted';
        }

        if(Auth::user()->can('logbook.view.other') && Auth::user()->cannot('logbook.view.own')) {
            $rules['only_own'] = 'prohibited';
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        abort(403);
    }
}
