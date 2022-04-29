<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class RoleUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required|unique:roles,name,'.$this->role->id,
        ];

        foreach (Permission::select('name')->pluck('name') as $permission) {
            $rules[str_replace('.', '_', $permission)] = 'accepted|sometimes';
        }

        return $rules;
    }
}
