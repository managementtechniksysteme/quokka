<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'name_2' => $this->faker->optional()->companySuffix,
            'phone' => $this->faker->optional()->phoneNumber,
            'fax' => $this->faker->optional()->phoneNumber,
            'email' => $this->faker->optional()->email,
            'website' => $this->faker->optional()->url,
            'comment' => $this->faker->optional()->realText(),
        ];
    }
}
