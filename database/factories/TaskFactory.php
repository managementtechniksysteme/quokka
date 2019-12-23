<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'starts_on' => $faker->optional()->date(),
        'ends_on' => $faker->optional()->date(),
        'due_on' => $faker->optional()->date(),
        'private' => $faker->boolean,
        'priority' => $faker->randomElement(['low', 'medium', 'high']),
        'status' => $faker->randomElement(['new', 'in progress', 'finished']),
        'billed' => $faker->randomElement(['yes', 'no', 'warranty']),
        'comment' => $faker->optional()->realText(),
    ];
});
