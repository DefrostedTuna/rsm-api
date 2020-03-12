<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'location_id' => null,
        'rating' => $faker->numberBetween(1, 5),
    ];
});
