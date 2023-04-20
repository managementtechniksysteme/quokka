<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->employee_id,
            'username' => $this->username,
            'username_avatar_string' => $this->username_avatar_string,
            'avatar_colour' => $this->settings->avatar_colour,
            'first_name' => $this->employee->person->first_name,
            'last_name' => $this->employee->person->last_name,
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'settings' => [
                'accounting_filter_default_days' => $this->settings->accounting_filter_default_days
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
