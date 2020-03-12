<?php

namespace App\Http\Controllers;

use App\Contracts\Services\LocationService;
use App\Contracts\Services\RatingService;
use App\Http\Requests\CreateRatingFormRequest;
use App\Http\Resources\Rating as RatingResource;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    /**
     * Instance of the Location Service.
     *
     * @var \App\Contracts\Services\LocationService
     */
    protected $locationService;

    /**
     * Instance of the Rating Service.
     *
     * @var \App\Contracts\Services\RatingService
     */
    protected $ratingService;

    /**
     * Created a new RatingController instance.
     *
     * @param  \App\Contracts\Services\LocationService  $locationService
     * @param  \App\Contracts\Services\RatingService    $ratingService
     *
     * @return void
     */
    public function __construct(LocationService $locationService, RatingService $ratingService)
    {
        $this->locationService = $locationService;
        $this->ratingService = $ratingService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateRatingFormRequest  $request
     * @param  string                                      $locationId
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo extract the rating to a validation rule to make sure it stays between 1 and 5.
     */
    public function store(CreateRatingFormRequest $request, string $locationId): JsonResponse
    {
        try {
            $location = $this->locationService->findOrFail($locationId);
            $rating = $this->ratingService->updateOrCreate($request->user()->id, $location->id, $request->get('rating'));
    
            return new JsonResponse([
                'success' => true,
                'message' => 'Rating successful.',
                'data' => new RatingResource($rating),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'There was a problem submitting the rating.',
            ], 500);
        }
    }
}
