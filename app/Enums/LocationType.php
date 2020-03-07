<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static LocationType WELCOME_CENTER()
 * @method static LocationType REST_STOP()
 * @method static LocationType CAMPGROUND()
 * @method static LocationType PARKING_LOT()
 * @method static LocationType TRAVEL_CENTER()
 */
class LocationType extends Enum
{
    private const WELCOME_CENTER = 'welcome_center';
    private const REST_STOP = 'rest_stop';
    private const CAMPGROUND = 'campground';
    private const PARKING_LOT = 'parking_lot';
    private const TRAVEL_CENTER = 'travel_center';
}