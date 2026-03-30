<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    $date = $faker->dateTimeThisYear;
    return [
        'name' => 'A '.$faker->firstName.' '.$faker->lastName, //Testing A
        'email' => $faker->unique()->safeEmail,
        'remember_token' => '',
        'telephone' => $faker->e164PhoneNumber,
        'password' => bcrypt('PW5565**'),
        //'role' => 'agent',
        'created_at' => $date,
        'updated_at' => $date
    ];
});
