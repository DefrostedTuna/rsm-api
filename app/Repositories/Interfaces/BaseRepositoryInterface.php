<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface {

    /**
     * Fetches the model being usd throughout the instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): \Illuminate\Database\Eloquent\Model;

    /**
     * Creates a record with the given attribute values.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Array $attributes): \Illuminate\Database\Eloquent\Model;

    /**
     * Fetches all records in a database table.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Finds and returns a record by its primary key.
     *
     * @param String $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(String $id): \Illuminate\Database\Eloquent\Model;

    /**
     * Updates a record with the given attribute values.
     *  
     * @param String $id
     * @param Array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(String $id, Array $attributes): \Illuminate\Database\Eloquent\Model;

    /**
     * Deletes a record from the system identified by its primary key.
     *
     * @param String $id
     *
     * @return Bool
     */
    public function delete(String $id): Bool;
}