<?php

namespace Database\Factories;

use App\Models\ApplicationSettings;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationSettingsFactory extends Factory
{
    protected $model = ApplicationSettings::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'services_hour_unit' => 'h',
        ];
    }
}
