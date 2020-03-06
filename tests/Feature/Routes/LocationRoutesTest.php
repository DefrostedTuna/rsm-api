<?php

namespace Tests\Feature\Routes;

use App\Enums\Amenities;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_a_list_of_locations()
    {
        $locations = factory(Location::class, 10)->create();

        $response = $this->get('/api/locations');

        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    /** @test */
    public function it_returns_an_individual_location()
    {
        $location = factory(Location::class)->create();

        $response = $this->get("/api/locations/{$location->id}");

        $response->assertStatus(200);
        $response->assertJson($location->toArray());
    }

    /** @test */
    public function it_can_store_a_location() 
    {
        $location = factory(Location::class)->make();

        $response = $this->post("/api/locations", $location->toArray());

        $storedLocation = (new Location())->first();

        $response->assertStatus(201);
        $this->assertEquals($storedLocation->toArray(), $response->getData(true));
        $response->assertJson($storedLocation->toArray());
    }

    /** @test */
    public function it_can_update_a_location()
    {
        $location = factory(Location::class)->create();

        $updatedAttributes = [
            'id'                => $location->id, // To make it easier to compare.
            'place_id'          => 'someRandomStringFromGoogle',
            'name'              => 'That Exit Along The Highway',
            'locale'            => 'Tampa',
            'state'             => 'Florida',
            'interstate'        => '4',
            'exit'              => '9001',
            'lat'               => 49.112481,
            'lng'               => -112.92718,
            'type'              => 'Welcome Center',
            'direction'         => 'Westbound',
            'status'            => 'Open',
            'condition'         => 'Fair',
            'amenities'         => [
                (string) Amenities::OVERNIGHT_PARKING(),
            ],
            'parking_duration'  => 30,
            'parking_spaces'    => [
                'car'           => 40,
                'truck'         => 30,
                'handicapped'   => 15,
            ],
            'cell_service'      => [
                'att'           => 5,
                'tmobile'       => 4,
                'verizon'       => 5,
                'sprint'        => 2,
            ],
        ];

        $response = $this->patch("/api/locations/{$location->id}", $updatedAttributes);

        $storedLocation = (new Location())->first();

        $response->assertStatus(200);
        $response->assertJson($updatedAttributes);
    }

    /** @test */
    public function it_can_delete_a_location()
    {
        $location = factory(Location::class)->create();

        $response = $this->delete("/api/locations/{$location->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing((new Location())->getTable(), [ 'id' => $location->id ]);
    }
}
