<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'street_number' => $faker->streetAddress,
        'postcode' => $faker->postcode,
        'city' => $faker->city,
    ];
});
