<?php

namespace Tests\Unit\Controllers;

use App\Contracts\Services\LocationService;
use App\Enums\Amenity;
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

        $locationService = $this->app->make(LocationService::class);
        $locationController = new LocationController($locationService);

        $response = $locationController->index();

        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Successfully retrieved data.',
            'data' => $locations->toArray(),
        ]);
    }

    /** @test */
    public function it_returns_an_individual_location()
    {
        $location = factory(Location::class)->create();

        $locationService = $this->app->make(LocationService::class);
        $locationController = new LocationController($locationService);

        $response = $locationController->show($location->id);

        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Successfully retrieved data.',
            'data' => $location->toArray(),
        ]);
    }

    /** @test */
    public function it_can_store_a_location()
    {
        $location = factory(Location::class)->make();

        /** @var \App\Http\Requests\CreateLocationFormRequest */
        $request = $this->mock(CreateLocationFormRequest::class, function ($mock) use ($location) {
            $mock->shouldReceive('validated')->andReturn($location->toArray());
        });

        $locationService = $this->app->make(LocationService::class);
        $locationController = new LocationController($locationService);

        $response = $locationController->store($request);

        $record = (new Location())->first();

        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Successfully created the record.',
            'data' => $record->toArray(),
        ]);
    }

    /** @test */
    public function it_can_update_a_location()
    {
        $location = factory(Location::class)->create();

        $attributes = [
            'id'                => $location->id,
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
                (string) Amenity::OVERNIGHT_PARKING(),
            ],
            'parking_duration'  => 30,
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

        $locationService = $this->app->make(LocationService::class);
        $locationController = new LocationController($locationService);

        $response = $locationController->update($request, $location->id);

        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Successfully updated the record.',
            'data' => $attributes,
        ]);
    }

    /** @test */
    public function it_can_delete_a_location()
    {
        $location = factory(Location::class)->create();

        $locationService = $this->app->make(LocationService::class);
        $locationController = new LocationController($locationService);

        $response = $locationController->destroy($location->id);

        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Successfully deleted the record.',
            'data' => true,
        ]);

        $this->assertDatabaseMissing((new Location())->getTable(), ['id' => $location->id]);
    }
}
