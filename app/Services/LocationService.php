<?php

namespace App\Services;

use App\Contracts\Services\LocationService as LocationServiceContract;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LocationService implements LocationServiceContract
{
    /**
     * The instance of the model to use used.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Sets the model to be used throughout the instance.
     *
     * @param  \App\Models\Location  $location
     * 
     * @return void
     */
    public function __construct(Location $location)
    {
        $this->model = $location;
    }

    /**
     * Creates a record with the given attribute values.
     *
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $input): Model
    {
        $location = $this->model->newInstance();

        $location->fill($input)->save();

        return $location;
    }

    /**
     * Fetches all records in a database table.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        // If pagination is required, parameters can be passed to a paginate function to indicate how many records to
        // return for each query, while also indicating the page number (or offset)
        // in which to apply the context.

        return $this->model->all();
    }

    /**
     * Finds and returns a record by its primary key.
     *
     * @param  string  $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Updates a record with the given attribute values.
     *
     * @param  string  $locationId
     * @param  array   $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(string $locationId, array $attributes): Model
    {
        $location = $this->findOrFail($locationId);

        $location->fill($attributes)->save();

        return $location;
    }

    /**
     * Deletes a record from the system identified by its primary key.
     *
     * @param  string  $id
     *
     * @return bool
     */
    public function delete(string $locationId): bool
    {
        // TODO: Create a check to make sure only administrators can delete a location.
        $location = $this->findOrFail($locationId);

        return $location->delete();
    }
}
