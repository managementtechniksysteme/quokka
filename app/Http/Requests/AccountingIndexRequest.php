<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountingIndexRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'start' => 'sometimes|date',
            'end' => 'sometimes|date',
            'project_id' => 'sometimes|exists:projects,id',
            'service_id' => 'sometimes|exists:services,id',
            'only_own' => 'sometimes|accepted',
        ];

        if($this->filled('start') && $this->filled('end')) {
            $rules['start'] = $rules['start'] . '|before_or_equal:end';
            $rules['end'] = $rules['end'] . '|after_or_equal:start';
        }

        if(Auth::user()->can('accounting.view.own') && Auth::user()->cannot('accounting.view.other')) {
            $rules['only_own'] = 'required|accepted';
        }

        if(Auth::user()->can('accounting.view.other') && Auth::user()->cannot('accounting.view.own')) {
            $rules['only_own'] = 'prohibited';
        }

        return $rules;
    }
}
