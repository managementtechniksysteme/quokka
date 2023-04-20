<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Logbook */
class LogbookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driven_on' => $this->driven_on,
            'start_kilometres' => $this->start_kilometres,
            'end_kilometres' => $this->end_kilometres,
            'driven_kilometres' => $this->driven_kilometres,
            'litres_refuelled' => $this->litres_refuelled,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'comment' => $this->comment,
            'employee_name' => $this->employee->person->name,
            'project_name' => $this->project?->name ?? null,
            'vehicle_registration_identifier' => $this->vehicle->registration_identifier,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'employee_id' => $this->employee_id,
            'project_id' => $this->project_id,
            'vehicle_id' => $this->vehicle_id,
        ];
    }
}
