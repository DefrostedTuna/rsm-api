<?php

namespace App\Http\Controllers;

use App\Contracts\Services\LocationService;
use App\Http\Requests\CreateLocationFormRequest;
use App\Http\Requests\UpdateLocationFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Instance of the Location Service.
     *
     * @var \App\Contracts\Services\LocationService $locationService
     */
    protected $locationService;

    /**
     * Create a new LocationController instance.
     *
     * @param \App\Contracts\Services\LocationService $locationService
     *
     * @return void
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully retrieved data.',
            'data' => $this->locationService->all(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateLocationFormRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateLocationFormRequest $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully created the record.',
            'data' => $this->locationService->create($request->validated()),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $locationId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $locationId): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully retrieved data.',
            'data' => $this->locationService->findOrFail($locationId),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocationFormRequest $request
     * @param  string                                       $locationId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLocationFormRequest $request, string $locationId): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully updated the record.',
            'data' => $this->locationService->update($locationId, $request->validated()),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $locationId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $locationId): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully deleted the record.',
            'data' => $this->locationService->delete($locationId),
        ], 200);
    }
}
