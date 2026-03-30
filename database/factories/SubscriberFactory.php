<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Subscriber::class, function (Faker $faker) {
    $date = $faker->dateTimeThisYear;
    return [
        'code' => Str::random(10),
        'email' => $faker->email,
        'created_at' => $date,
        'updated_at' => $date
    ];
});
