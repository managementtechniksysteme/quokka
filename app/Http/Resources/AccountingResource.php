<?php

namespace App\Http\Resources;

use App\Models\ApplicationSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Accounting */
class AccountingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_provided_on' => $this->service_provided_on,
            'service_provided_started_at' => $this->service_provided_started_at,
            'service_provided_ended_at' => $this->service_provided_ended_at,
            'amount' => $this->amount,
            'comment' => $this->comment,
            'employee_name' => $this->employee->person->name,
            'project_name' => $this->project->name,
            'service_name' => $this->service->name,
            'service_type' => $this->service->type,
            'service_unit' => $this->service->unit_string,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'employee_id' => $this->employee_id,
            'project_id' => $this->project_id,
            'service_id' => $this->service_id,
        ];
    }
}
