<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Location;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'place_id'          => $faker->uuid,
        'name'              => $faker->word,
        'locale'            => $faker->city,
        'state'             => $faker->state,
        'interstate'        => $faker->numberBetween(1, 999),
        'exit'              => $faker->numberBetween(1, 999),
        'lat'               => $faker->latitude,
        'lng'               => $faker->longitude,
        'type'              => $faker->word,
        'direction'         => $faker->word,
        'status'            => $faker->word,
        'condition'         => $faker->word,
        'potable_water'     => $faker->boolean(50),
        'overnight_parking' => $faker->boolean(50),
        'parking_duration'  => $faker->numberBetween(15, 480),
        'restrooms'         => $faker->boolean(90),
        'family_restroom'   => $faker->boolean(50),
        'dump_station'      => $faker->boolean(25),
        'pet_area'          => $faker->boolean(50),
        'vending'           => $faker->boolean(75),
        'security'          => $faker->boolean(25),
        'indoor_area'       => $faker->boolean(50),
        'parking_spaces'    => [
            'car'           => $faker->numberBetween(1, 100),
            'truck'         => $faker->numberBetween(1, 100),
            'handicapped'   => $faker->numberBetween(1, 25),
        ],
        'cell_service'      => [
            'att'           => $faker->numberBetween(1, 5),
            'verizon'       => $faker->numberBetween(1, 5),
            'sprint'        => $faker->numberBetween(1, 5),
            'tmobile'       => $faker->numberBetween(1, 5),
        ],
    ];
});
