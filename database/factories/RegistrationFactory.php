<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Registration;
use Faker\Generator as Faker;

$factory->define(Registration::class, function (Faker $faker) {
    return [
        'start_time' => $faker->dateTimeThisMonth,
        'end_time' => $faker->dateTimeThisMonth,
        'created_at' => $faker->dateTimeThisMonth
    ];
});
