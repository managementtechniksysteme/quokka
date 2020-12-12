<?php
namespace Database\Factories;

use App\Models\Address;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'title_prefix' => $this->faker->optional()->title,
            'title_suffix' => $this->faker->optional()->title,
            'gender' => $this->faker->randomElement(['male', 'female', 'neutral']),
            'department' => $this->faker->optional()->word,
            'role' => $this->faker->optional()->jobTitle,
            'phone_company' => $this->faker->optional()->phoneNumber,
            'phone_mobile' => $this->faker->optional()->phoneNumber,
            'phone_private' => $this->faker->optional()->phoneNumber,
            'fax' => $this->faker->optional()->phoneNumber,
            'email' => $this->faker->companyEmail,
            'website' => $this->faker->optional()->url,
            'comment' => $this->faker->optional()->text,
        ];
    }
}
