<?php

namespace App\Contracts\Services;

use App\Models\Rating;

interface RatingService
{
    /**
     * Updates an existing rating, creating a new entry if an existing record is not found.
     *
     * @param  string  $userId
     * @param  string  $locationId
     * @param  int     $rating
     *
     * @return \App\Models\Rating
     */
    public function updateOrCreate(string $userId, string $locationId, int $rating): Rating;
}