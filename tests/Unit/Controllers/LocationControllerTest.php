<?php

namespace Tests\Unit\Controllers;

use App\Contracts\Services\LocationService;
use App\Http\Controllers\LocationController;
use App\Http\Requests\CreateLocationFormRequest;
use App\Http\Requests\UpdateLocationFormRequest;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_a_list_of_locations()
    {
        $locations = factory(Location::class, 10)->create();

        $locationController = new LocationController($this->app->make(LocationService::class));

        $response = $locationController->index();

        $this->assertCount(10, $response->getData(true));
    }

    /** @test */
    public function it_returns_an_individual_location()
    {
        $location = factory(Location::class)->create();

        $locationController = new LocationController($this->app->make(LocationService::class));

        $response = $locationController->show($location->id);

        $this->assertEquals($location->toArray(), $response->getData(true));
    }

    /** @test */
    public function it_can_store_a_location()
    {
        $location = factory(Location::class)->make();

        /** @var \App\Http\Requests\CreateLocationFormRequest */
        $request = $this->mock(CreateLocationFormRequest::class, function ($mock) use ($location) {
            $mock->shouldReceive('validated')->andReturn($location->toArray());
        });

        $locationController = new LocationController($this->app->make(LocationService::class));

        $response = $locationController->store($request);

        $record = (new Location())->first(); // There should only be one record.
        
        $this->assertContains($location->toArray(), $response->getData(true));
        $this->assertEquals($record->toArray(), $response->getData(true));
    }

    /** @test */
    public function it_can_update_a_location()
    {
        $location = factory(Location::class)->create();

        $attributes = [
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
            'potable_water'     => true,
            'overnight_parking' => true,
            'parking_duration'  => 30,
            'restrooms'         => true,
            'family_restroom'   => true,
            'dump_station'      => false,
            'pet_area'          => true,
            'vending'           => true,
            'security'          => false,
            'indoor_area'       => true,
            'parking_spaces'    => [
                'car'           => 40,
                'truck'         => 30,
                'handicapped'   => 15,
            ],
            'cell_service'      => [
                'att'           => 5,
                'verizon'       => 5,
                'sprint'        => 2,
                'tmobile'       => 4,
            ],
        ];

        /** @var \App\Http\Requests\UpdateLocationFormRequest */
        $request = $this->mock(UpdateLocationFormRequest::class, function ($mock) use ($attributes) {
            $mock->shouldReceive('validated')->andReturn($attributes);
        });

        $locationController = new LocationController($this->app->make(LocationService::class));

        $response = $locationController->update($request, $location->id);

        $updatedLocation = (new Location())->first();

        $this->assertEquals($attributes, $response->getData(true));
    }

    /** @test */
    public function it_can_delete_a_location()
    {
        $location = factory(Location::class)->create();

        $locationController = new LocationController($this->app->make(LocationService::class));

        $locationController->destroy($location->id);

        $this->assertDatabaseMissing((new Location())->getTable(), [ 'id' => $location->id ]);
    }
}
