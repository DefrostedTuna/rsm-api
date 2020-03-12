<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface LocationService
{
    /**
     * Creates a record with the given attribute values.
     *
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): Model;

    /**
     * Fetches all records in a database table.
     * Relationships can be loaded dynamically using parameters.
     *
     * @param  array  $relations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $relations = []): Collection;

    /**
     * Finds and returns a record by its primary key.
     * Relationships can be loaded dynamically using parameters.
     *
     * @param  string  $id
     * @param  array   $relations
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id, array $relations = []): Model;

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
    public function update(string $locationId, array $attributes, array $relations = []): Model;

    /**
     * Deletes a record from the system identified by its primary key.
     *
     * @param  string  $id
     *
     * @return bool
     */
    public function delete(string $id): bool;
}
