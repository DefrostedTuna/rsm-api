<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Uuids;

    /** 
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The 'type' of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_id',
        'name',
        'locale',
        'state',
        'interstate',
        'exit',
        'lat',
        'lng',
        'type',
        'direction',
        'status',
        'condition',
        'potable_water',
        'overnight_parking',
        'parking_duration',
        'restrooms',
        'family_restroom',
        'dump_station',
        'pet_area',
        'vending',
        'security',
        'indoor_area',
        'parking_spaces',
        'cell_service',
    ];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'place_id',
        'name',
        'locale',
        'state',
        'interstate',
        'exit',
        'lat',
        'lng',
        'type',
        'direction',
        'status',
        'condition',
        'potable_water',
        'overnight_parking',
        'parking_duration',
        'restrooms',
        'family_restroom',
        'dump_station',
        'pet_area',
        'vending',
        'security',
        'indoor_area',
        'parking_spaces',
        'cell_service',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array $hidden
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array $casts
     */
    protected $casts = [
        'id'                => 'string',
        'place_id'          => 'string',
        'name'              => 'string',
        'locale'            => 'string',
        'state'             => 'string',
        'interstate'        => 'string',
        'exit'              => 'string',
        'lat'               => 'float',
        'lng'               => 'float',
        'type'              => 'string',
        'direction'         => 'string',
        'status'            => 'string',
        'condition'         => 'string',
        'potable_water'     => 'boolean',
        'overnight_parking' => 'boolean',
        'parking_duration'  => 'integer',
        'restrooms'         => 'boolean',
        'family_restroom'   => 'boolean',
        'dump_station'      => 'boolean',
        'pet_area'          => 'boolean',
        'vending'           => 'boolean',
        'security'          => 'boolean',
        'indoor_area'       => 'boolean',
        'parking_spaces'    => 'array',
        'cell_service'      => 'array',
    ];
}
