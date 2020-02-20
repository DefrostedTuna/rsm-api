<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
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
    public function create(array $attributes): \App\Models\User
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
    public function findOrFail(string $id): \App\Models\User
    {
        return $this->model->findOrFail($id);
    }
}
