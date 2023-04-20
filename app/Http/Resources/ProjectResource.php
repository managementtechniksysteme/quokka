<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starts_on' => $this->starts_on,
            'ends_on' => $this->ends_on,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'material_costs' => $this->material_costs,
            'wage_costs' => $this->wage_costs,
            'is_pre_execution' => $this->is_pre_execution,
            'include_in_finances' => $this->include_in_finances,
            'costs' => $this->costs,
            'current_billed_costs_percentage' => $this->current_billed_costs_percentage,
            'current_billed_costs_status' => $this->current_billed_costs_status,
            'current_costs' => $this->current_costs,
            'current_costs_percentage' => $this->current_costs_percentage,
            'current_costs_status' => $this->current_costs_status,
            'current_kilometre_costs' => $this->current_kilometre_costs,
            'current_kilometres' => $this->current_kilometres,
            'current_material_costs' => $this->current_material_costs,
            'current_material_costs_percentage' => $this->current_material_costs_percentage,
            'current_material_costs_status' => $this->current_material_costs_status,
            'current_wage_costs' => $this->current_wage_costs,
            'current_wage_costs_percentage' => $this->current_wage_costs_percentage,
            'current_wage_costs_status' => $this->current_wage_costs_status,
            'included_in_finances_string' => $this->included_in_finances_string,
            'is_pre_execution_string' => $this->is_pre_execution_string,
            'short_name' => $this->short_name,

            'company_id' => $this->company_id,
        ];
    }
}
