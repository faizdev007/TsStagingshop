<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\PropertyType::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'created_at' => Carbon::now(),
    ];
});
