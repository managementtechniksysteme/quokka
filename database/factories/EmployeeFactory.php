<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Person;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'person_id' => factory(Person::class),
        'entered_on' => $faker->date(),
        'left_on' => $faker->optional()->date(),
        'holidays' => $faker->numberBetween(0, 100),
    ];
});
