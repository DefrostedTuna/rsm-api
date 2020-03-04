<?php

namespace App\Services;

use App\Contracts\Services\UserService as UserServiceContract;
use App\Events\Auth\PasswordChanged;
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
     * 
     * @return void
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

    /**
     * Sets the given User's password to the provided value.
     *
     * Also fires the PasswordChanged Event.
     *
     * @param  \App\Models\User  $user
     * @param  string            $password
     *
     * @return \App\Models\User
     */
    public function setPassword(User $user, string $password): User
    {
        $user->password = bcrypt($password);
        $user->save();

        event(new PasswordChanged($user));

        return $user;
    }
}
