<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $date = $faker->dateTimeThisYear;
    return [
        'title' => $faker->text(50),
        'content' => $faker->text(5000),
        'status' => 'published',
        'date_published' => $date,
        'created_at' => $date,
        'updated_at' => Carbon::now()
    ];
});
