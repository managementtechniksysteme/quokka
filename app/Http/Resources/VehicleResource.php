<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vehicle */
class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'make' => $this->make,
            'model' => $this->model,
            'registration_identifier' => $this->registration_identifier,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'private' => $this->private,
            'current_kilometres_string' => $this->current_kilometres_string,
            'make_model' => $this->make_model,
            'private_string' => $this->private_string,
            'get_current_kilometres_attribute_count' => $this->get_current_kilometres_attribute_count,
            'get_current_kilometres_string_attribute_count' => $this->get_current_kilometres_string_attribute_count,

            'getCurrentKilometresAttribute' => LogbookCollection::collection($this->whenLoaded('getCurrentKilometresAttribute')),
            'getCurrentKilometresStringAttribute' => LogbookCollection::collection($this->whenLoaded('getCurrentKilometresStringAttribute')),
        ];
    }
}
