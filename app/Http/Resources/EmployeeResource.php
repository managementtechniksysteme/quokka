<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Employee */
class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->person_id,
            'entered_on' => $this->entered_on,
            'left_on' => $this->left_on,
            'holidays' => $this->holidays,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'person_id' => $this->person_id,
        ];
    }
}
