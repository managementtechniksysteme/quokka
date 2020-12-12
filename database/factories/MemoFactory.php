<?php

namespace Database\Factories;

use App\Models\Memo;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemoFactory extends Factory
{
    protected $model = Memo::class;

    public function definition()
    {
        return [
            'number' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence,
            'meeting_held_on' => $this->faker->date(),
            'next_meeting_on' => $this->faker->optional()->date(),
            'comment' => $this->faker->realText(),
        ];
    }
}
