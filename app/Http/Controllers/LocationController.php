<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocationFormRequest;
use App\Http\Requests\UpdateLocationFormRequest;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Instance of the Location Model.
     *
     * @var \App\Repositories\Interfaces\LocationRepositoryInterface $locationRepository
     */
    protected $locationRepository;

    /**
     * Sets the LocationRepository instance to be used throughout the controller.
     *
     * @param \App\Repositories\Interfaces\LocationRepository $locationRepository
     * 
     * @return void
     */
    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return new JsonResponse($this->locationRepository->all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\CreateLocationFormRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateLocationFormRequest $request): \Illuminate\Http\JsonResponse
    {
        return new JsonResponse($this->locationRepository->create($request->input()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param String $locationId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(String $locationId): \Illuminate\Http\JsonResponse
    {
        return new JsonResponse($this->locationRepository->findOrFail($locationId), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\UpdateLocationFormRequest $request
     * @param String $locationId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLocationFormRequest $request, String $locationId): \Illuminate\Http\JsonResponse
    {
        return new JsonResponse($this->locationRepository->update($locationId, $request->toArray()), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $locationId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(String $locationId): \Illuminate\Http\JsonResponse
    {
        return new JsonResponse($this->locationRepository->delete($locationId), 200);
    }
}
