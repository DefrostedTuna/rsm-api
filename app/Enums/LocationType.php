<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Amenities WELCOME_CENTER()
 * @method static Amenities REST_STOP()
 * @method static Amenities CAMPGROUND()
 * @method static Amenities PARKING_LOT()
 * @method static Amenities TRAVEL_CENTER()
 */
class LocationType extends Enum
{
    private const WELCOME_CENTER = 'welcome_center';
    private const REST_STOP = 'rest_stop';
    private const CAMPGROUND = 'campground';
    private const PARKING_LOT = 'parking_lot';
    private const TRAVEL_CENTER = 'travel_center';
}