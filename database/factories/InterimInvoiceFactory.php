<?php

namespace Database\Factories;

use App\Models\InterimInvoice;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterimInvoiceFactory extends Factory
{
    protected $model = InterimInvoice::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'billed_on' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'comment' => $this->faker->optional()->sentence,
            'project_id' => Project::factory(),
        ];
    }
}
