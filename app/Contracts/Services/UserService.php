<?php

namespace App\Contracts\Services;

use App\Models\User;

interface UserService
{
    /**
     * Creates a record with the given attribute values.
     *
     * @param  array  $attributes
     *
     * @return \App\Models\User
     */
    public function create(array $attributes): User;

    /**
     * Finds and returns a record by its primary key.
     *
     * @param  string  $id
     *
     * @return \App\Models\User
     */
    public function findOrFail(string $id): User;
}
