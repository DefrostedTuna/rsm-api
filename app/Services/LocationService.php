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
     * Relationships can be loaded dynamically using parameters.
     *
     * @param  array  $relations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $relations = []): Collection
    {
        // If pagination is required, parameters can be passed to a paginate function to indicate how many records to
        // return for each query, while also indicating the page number (or offset)
        // in which to apply the context.

        $query = $this->model->newInstance();

        if (! empty($relations) && count($relations) > 0) {
            $query = $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Finds and returns a record by its primary key.
     * Relationships can be loaded dynamically using parameters.
     *
     * @param  string  $id
     * @param  array   $relations
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id, array $relations = []): Model
    {
        $query = $this->model->newInstance();

        if (! empty($relations) && count($relations) > 0) {
            $query = $query->with($relations);
        }

        return $query->findOrFail($id);
    }

    /**
     * Updates a record with the given attribute values.
     * Relationships can be loaded dynamically using parameters.
     *
     * @param  string  $locationId
     * @param  array   $attributes
     * @param  array   $relations
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(string $locationId, array $attributes, array $relations = []): Model
    {
        $location = $this->findOrFail($locationId, $relations);

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
