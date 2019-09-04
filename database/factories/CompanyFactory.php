<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'name_2' => $faker->optional()->companySuffix,
        'phone' => $faker->optional()->phoneNumber,
        'fax' => $faker->optional()->phoneNumber,
        'email' => $faker->optional()->email,
        'website' => $faker->optional()->url,
        'comment' => $faker->optional()->realText(),
    ];
});
