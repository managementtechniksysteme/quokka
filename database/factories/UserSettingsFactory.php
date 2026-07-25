<?php

namespace Database\Factories;

use App\Models\UserSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingsFactory extends Factory
{
    protected $model = UserSettings::class;

    public function definition()
    {
        return [
            'avatar_colour' => $this->faker->randomElement(array_column(UserSettings::avatarColours, 'label')),
        ];
    }
}
