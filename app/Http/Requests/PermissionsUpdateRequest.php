<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class PermissionsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'role_id' => 'sometimes|exists:roles,id'
        ];

        foreach (Permission::select('name')->pluck('name') as $permission) {
            $rules[str_replace('.', '_', $permission)] = 'accepted|sometimes';
        }

        return $rules;
    }
}
