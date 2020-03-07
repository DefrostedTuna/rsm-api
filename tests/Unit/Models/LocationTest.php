<?php

namespace Tests\Unit\Models;

use App\Models\Location;
use Tests\TestCase;

class LocationTest extends TestCase
{

    /** @test */
    public function it_uses_the_proper_database_table()
    {
        $location = new Location();

        $this->assertEquals('locations', $location->getTable());
    }

    /** @test */
    public function it_sets_the_primary_key_type_to_string()
    {
        $location = new Location();

        $this->assertEquals('string', $location->getKeyType());
    }

    /** @test */
    public function it_explicitly_defines_the_properties_to_be_assigned_in_mass()
    {
        $location = new Location();

        $expected = [
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
            'amenities',
            'parking_duration',
            'parking_spaces',
            'cell_service',
        ];

        $this->assertEquals($expected, $location->getFillable());
    }

    /** @test */
    public function it_explicitly_defines_the_visible_fields_for_api_consumption()
    {
        $location = new Location();

        $visibleFields = [
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
            'amenities',
            'parking_duration',
            'parking_spaces',
            'cell_service',
        ];

        $this->assertEquals($visibleFields, $location->getVisible());
    }

    /** @test */
    public function it_explicitly_defines_the_hidden_fields_for_api_consumption()
    {
        $location = new Location();

        $hiddenFields = [
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $this->assertEquals($hiddenFields, $location->getHidden());
    }

    /** @test */
    public function it_explicitly_defines_the_return_type_for_each_field() 
    {
        $location = new Location();

        $fields = $location->getCasts();

        $expected = [
            'id'                => 'string',
            'place_id'          => 'string',
            'name'              => 'string',
            'locale'            => 'string',
            'state'             => 'string',
            'interstate'        => 'string',
            'exit'              => 'string',
            'lat'               => 'double',
            'lng'               => 'double',
            'type'              => 'string',
            'direction'         => 'string',
            'status'            => 'string',
            'condition'         => 'string',
            'amenities'         => 'array',
            'parking_duration'  => 'integer',
            'parking_spaces'    => 'array',
            'cell_service'      => 'array',
        ];

        $this->assertEquals($expected, $fields);
    }
}
