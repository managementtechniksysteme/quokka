<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'street_number' => $this->faker->streetAddress,
            'postcode' => $this->faker->postcode,
            'city' => $this->faker->city,
        ];
    }
}
