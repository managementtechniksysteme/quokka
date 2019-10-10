<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'starts_on' => $faker->optional()->date(),
        'ends_on' => $faker->optional()->date(),
        'comment' => $faker->optional()->realText(),
    ];
});
