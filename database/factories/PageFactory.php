<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Page::class, function (Faker $faker) {
    return [
        //'title' => substr($faker->text(50), 0, -1),
        //'content' => $faker->realText(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
