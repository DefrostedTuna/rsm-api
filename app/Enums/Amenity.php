<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Amenity POTABLE_WATER()
 * @method static Amenity OVERNIGHT_PARKING()
 * @method static Amenity RESTROOMS()
 * @method static Amenity FAMILY_RESTROOM()
 * @method static Amenity DUMP_STATION()
 * @method static Amenity PET_AREA()
 * @method static Amenity VENDING()
 * @method static Amenity SECURITY()
 * @method static Amenity INDOOR_AREA()
 */
class Amenity extends Enum
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