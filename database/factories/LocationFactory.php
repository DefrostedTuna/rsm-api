<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\Amenity;
use App\Enums\LocationType;
use App\Models\Location;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    // A bit gross looking, but this will randomly assign amenities to the model.
    $amenities = Amenity::toArray();
    $numberOfSelectedAmenities = $faker->numberBetween(0, count($amenities));
    $selectedAmenities = [];

    while ($numberOfSelectedAmenities > 0) {
        $amenity = array_rand($amenities);
        $selectedAmenities[] = $amenities[$amenity];
        unset($amenities[$amenity]);
        $numberOfSelectedAmenities--;
    }

    return [
        'type'              => $faker->randomElement(LocationType::toArray()),
        'google_place_id'   => $faker->uuid,
        'name'              => $faker->word,
        'locale'            => $faker->city,
        'state'             => $faker->state,
        'interstate'        => $faker->numberBetween(1, 999),
        'exit'              => $faker->numberBetween(1, 999),
        'lat'               => $faker->latitude,
        'lng'               => $faker->longitude,
        'direction'         => $faker->word,
        'status'            => $faker->word,
        'condition'         => $faker->word,
        'amenities'         => $selectedAmenities,
        'parking_duration'  => $faker->numberBetween(15, 480),
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
