<?php

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LocationRepository implements LocationRepositoryInterface
{
    protected $model;

    public function __construct(Location $location)
    {
        $this->model = $location;
    }

    /**
     * Creates a record with the given attribute values.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Array $input): \Illuminate\Database\Eloquent\Model
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
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        // If pagination is required, parameters can be passed to a paginate function to indicate how many records to
        // return for each query, while also indicating the page number (or offset) 
        // in which to apply the context.

        return $this->model->all();
    }

    /**
     * Finds and returns a record by its primary key.
     *
     * @param String $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(String $id): \Illuminate\Database\Eloquent\Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Updates a record with the given attribute values.
     *  
     * @param String $locationId
     * @param Array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(String $locationId, Array $attributes): \Illuminate\Database\Eloquent\Model
    {
        $location = $this->findOrFail($locationId);

        $location->fill($attributes)->save();

        return $location;
    }

    /**
     * Deletes a record from the system identified by its primary key.
     *
     * @param String $id
     *
     * @return Bool
     */
    public function delete(String $locationId): Bool
    {
        // TODO: Create a check to make sure only administrators can delete a location.
        $location = $this->findOrFail($locationId);

        return $location->delete();
    }
}