<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Testimonial::class, function (Faker $faker) {
    $date = $faker->dateTimeThisYear;
    return [
        'name' => $faker->name,
        'location' => $faker->city,
        'quote' => $faker->text,
        'date' => $date,
        'priority' => $faker->unique()->numberBetween(1,20),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
