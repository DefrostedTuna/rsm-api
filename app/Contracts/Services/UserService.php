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

    /**
     * Sets the given User's password to the provided value.
     *
     * @param  \App\Models\User  $user
     * @param  string            $password
     *
     * @return \App\Models\User
     */
    public function setPassword(User $user, string $password): User;
}
