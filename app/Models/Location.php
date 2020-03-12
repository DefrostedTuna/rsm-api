<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'ratings',
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

    /**
     * The Ratings that have been given for the location.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'location_id', 'id');
    }

    /**
     * Calculates the average rating associated with the Location.
     *
     * @return int
     */
    public function avgRating(): int
    {
        return $this->ratings()->avg('rating') ?: 0;
    }
}
