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
     * @var \Illuminate\Database\Eloquent\Model
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $input): \Illuminate\Database\Eloquent\Model
    {
        $user = $this->model->newInstance();

        $user->fill([
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
        ])->save();

        return $user;
    }
}