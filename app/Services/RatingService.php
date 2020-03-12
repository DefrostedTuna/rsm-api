<?php

namespace App\Services;

use App\Contracts\Services\RatingService as ServicesRatingContract;
use App\Models\Rating;

class RatingService implements ServicesRatingContract
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
     * @param  \App\Models\Rating  $rating
     *
     * @return void
     */
    public function __construct(Rating $rating)
    {
        $this->model = $rating;
    }

    /**
     * Updates an existing rating, creating a new entry if an existing record is not found.
     *
     * @param  string  $userId
     * @param  string  $locationId
     * @param  int     $rating
     *
     * @return \App\Models\Rating
     */
    public function updateOrCreate(string $userId, string $locationId, int $rating): Rating
    {
        return $this->model->updateOrCreate([
            'user_id' => $userId,
            'location_id' => $locationId,
        ], [
            'rating' => $rating,
        ]);
    }
}
