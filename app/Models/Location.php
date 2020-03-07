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
        'type',
        'google_place_id',
        'name',
        'locale',
        'state',
        'interstate',
        'exit',
        'lat',
        'lng',
        'direction',
        'status',
        'condition',
        'amenities',
        'parking_duration',
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
        'type',
        'google_place_id',
        'name',
        'locale',
        'state',
        'interstate',
        'exit',
        'lat',
        'lng',
        'direction',
        'status',
        'condition',
        'amenities',
        'parking_duration',
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
        'type'              => 'string',
        'google_place_id'   => 'string',
        'name'              => 'string',
        'locale'            => 'string',
        'state'             => 'string',
        'interstate'        => 'string',
        'exit'              => 'string',
        'lat'               => 'double',
        'lng'               => 'double',
        'direction'         => 'string',
        'status'            => 'string',
        'condition'         => 'string',
        'amenities'         => 'array',
        'parking_duration'  => 'integer',
        'parking_spaces'    => 'array',
        'cell_service'      => 'array',
    ];
}
