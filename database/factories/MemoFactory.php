<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Memo;
use Faker\Generator as Faker;

$factory->define(Memo::class, function (Faker $faker) {
    return [
        'number' => $faker->randomNumber(),
        'title' => $faker->sentence,
        'meeting_held_on' => $faker->date(),
        'next_meeting_on' => $faker->optional()->date(),
        'comment' => $faker->realText(),
    ];
});
