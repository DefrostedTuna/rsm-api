<?php

namespace Tests\Unit\Models;

use App\Models\Rating;
use Tests\TestCase;

class RatingTest extends TestCase
{
    /** @test */
    public function it_uses_the_proper_database_table()
    {
        $rating = new Rating();

        $this->assertEquals('ratings', $rating->getTable());
    }

    /** @test */
    public function it_sets_the_primary_key_type_to_string()
    {
        $rating = new Rating();

        $this->assertEquals('string', $rating->getKeyType());
    }

    /** @test */
    public function it_explicitly_defines_the_properties_to_be_assigned_in_mass()
    {
        $rating = new Rating();

        $expected = [
            'user_id',
            'location_id',
            'rating',
        ];

        $this->assertEquals($expected, $rating->getFillable());
    }

    /** @test */
    public function it_explicitly_defines_the_visible_fields_for_api_consumption()
    {
        $rating = new Rating();

        $visibleFields = [
            'id',
            'user_id',
            'location_id',
            'rating',
        ];

        $this->assertEquals($visibleFields, $rating->getVisible());
    }

    /** @test */
    public function it_explicitly_defines_the_hidden_fields_for_api_consumption()
    {
        $rating = new Rating();

        $hiddenFields = [
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $this->assertEquals($hiddenFields, $rating->getHidden());
    }

    /** @test */
    public function it_explicitly_defines_the_return_type_for_each_field()
    {
        $rating = new Rating();

        $fields = $rating->getCasts();

        $expected = [
            'id'            => 'string',
            'user_id'       => 'string',
            'location_id'   => 'string',
            'rating'        => 'int',
        ];

        $this->assertEquals($expected, $fields);
    }
}
