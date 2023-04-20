<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Person */
class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'title_prefix' => $this->title_prefix,
            'title_suffix' => $this->title_suffix,
            'gender' => $this->gender,
            'department' => $this->department,
            'role' => $this->role,
            'phone_company' => $this->phone_company,
            'phone_mobile' => $this->phone_mobile,
            'phone_private' => $this->phone_private,
            'fax' => $this->fax,
            'email' => $this->email,
            'website' => $this->website,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'company_id' => $this->company_id,
        ];
    }
}
