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
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection;

    /**
     * Finds and returns a record by its primary key.
     *
     * @param  string  $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id): Model;

    /**
     * Updates a record with the given attribute values.
     *
     * @param  string  $id
     * @param  array   $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $attributes): Model;

    /**
     * Deletes a record from the system identified by its primary key.
     *
     * @param  string  $id
     *
     * @return bool
     */
    public function delete(string $id): bool;
}
