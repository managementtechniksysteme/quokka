<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ApplicationSettings */
class ApplicationSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'currency_unit' => $this->currency_unit,
            'accounting_min_amount' => $this->accounting_min_amount,
        ];
    }
}
