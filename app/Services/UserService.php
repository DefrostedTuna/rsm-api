<?php

namespace App\Services;

use App\Contracts\Services\UserService as UserServiceContract;
use App\Models\User as User;

class UserService implements UserServiceContract
{
    /**
     * The instance of the model to use used.
     *
     * @var \App\Models\User
     */
    protected $model;

    /**
     * Sets the model to be used throughout the instance.
     *
     * @param  \App\Models\User  $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Creates a record with the given attribute values.
     *
     * @param  array  $attributes
     *
     * @return \App\Models\User
     */
    public function create(array $attributes): User
    {
        $user = $this->model->newInstance();

        $user->fill([
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
        ])->save();

        return $user;
    }

    /**
     * Finds and returns a record by its primary key.
     *
     * @param  string  $id
     *
     * @return \App\Models\User
     */
    public function findOrFail(string $id): User
    {
        return $this->model->findOrFail($id);
    }
}
