<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Amenities POTABLE_WATER()
 * @method static Amenities OVERNIGHT_PARKING()
 * @method static Amenities RESTROOMS()
 * @method static Amenities FAMILY_RESTROOM()
 * @method static Amenities DUMP_STATION()
 * @method static Amenities PET_AREA()
 * @method static Amenities VENDING()
 * @method static Amenities SECURITY()
 * @method static Amenities INDOOR_AREA()
 */
class Amenities extends Enum
{
    private const POTABLE_WATER = 'potable_water';
    private const OVERNIGHT_PARKING = 'overnight_parking';
    private const RESTROOMS = 'restrooms';
    private const FAMILY_RESTROOM = 'family_restroom';
    private const DUMP_STATION = 'dump_station';
    private const PET_AREA = 'pet_area';
    private const VENDING = 'vending';
    private const SECURITY = 'security';
    private const INDOOR_AREA = 'indoor_area';
}