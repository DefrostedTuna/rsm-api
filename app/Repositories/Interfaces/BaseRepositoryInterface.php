<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface {
    /**
     * Creates a record with the given attribute values.
     *
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): \Illuminate\Database\Eloquent\Model;

    /**
     * Fetches all records in a database table.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Finds and returns a record by its primary key.
     *
     * @param  string  $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(string $id): \Illuminate\Database\Eloquent\Model;

    /**
     * Updates a record with the given attribute values.
     *  
     * @param  string  $id
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $attributes): \Illuminate\Database\Eloquent\Model;

    /**
     * Deletes a record from the system identified by its primary key.
     *
     * @param  string  $id
     *
     * @return bool
     */
    public function delete(string $id): bool;
}