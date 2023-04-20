<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Service */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'costs' => $this->costs,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name_with_unit' => $this->name_with_unit,
        ];
    }
}
