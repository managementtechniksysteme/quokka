<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'title_prefix' => $faker->optional()->title,
        'title_suffix' => $faker->optional()->title,
        'gender' => $faker->randomElement(['male', 'female', 'neutral']),
        'department' => $faker->optional()->word,
        'role' => $faker->optional()->jobTitle,
        'phone_company' => $faker->optional()->phoneNumber,
        'phone_mobile' => $faker->optional()->phoneNumber,
        'phone_private' => $faker->optional()->phoneNumber,
        'fax' => $faker->optional()->phoneNumber,
        'email' => $faker->companyEmail,
        'website' => $faker->optional()->url,
        'comment' => $faker->optional()->text,
    ];
});
