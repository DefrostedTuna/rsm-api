<?php

namespace App\Repositories\Interfaces;

interface LocationRepositoryInterface extends BaseRepositoryInterface {
    /**
     * Fetches the model being usd throughout the instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): \Illuminate\Database\Eloquent\Model;
}