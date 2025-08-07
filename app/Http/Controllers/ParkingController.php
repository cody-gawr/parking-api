<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parking\ClosestParkingRequest;
use App\Http\Requests\Parking\StoreParkingRequest;
use App\Http\Requests\Parking\UpdateParkingRequest;
use App\Services\ParkingService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Parkings
 * @authenticated
 *
 * CRUD operations and closest-parking lookup.
 */
class ParkingController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ParkingService $service)
    {
    }

    /**
     * List all parkings.
     *
     * @responseFile 200 docs/responses/parking/list-success.json
     */
    public function index(): JsonResponse
    {
        return response()->json($this->service->list());
    }

    /**
     * Create a new parking.
     *
     * @bodyParam Request \App\Http\Requests\Parking\StoreParkingRequest
     * @responseFile 201 docs/responses/parking/create-success.json
     * @responseFile 400 docs/responses/parking/create-error.json
     */
    public function store(StoreParkingRequest $request): JsonResponse
    {
        $parking = $this->service->create($request->validated());
        return response()->json($parking, Response::HTTP_CREATED);
    }

    /**
     * Retrieve a parking by ID.
     *
     * @urlParam id integer required The ID of the parking. Example: 1
     * @responseFile 200 docs/responses/parking/show-success.json
     * @responseFile 404 docs/responses/parking/show-error.json
     */
    public function show(int $id): JsonResponse
    {
        $parking = $this->service->get($id);
        return response()->json($parking);
    }

    /**
     * Update a parking.
     *
     * @bodyParam Request \App\Http\Requests\Parking\UpdateParkingRequest
     * @urlParam id integer required The ID of the parking. Example: 1
     * @responseFile 200 docs/responses/parking/update-success.json
     * @responseFile 400 docs/responses/parking/update-error.json
     */
    public function update(UpdateParkingRequest $request, int $id): JsonResponse
    {
        $parking = $this->service->update($id, $request->validated());
        return response()->json($parking);
    }

    /**
     * Delete a parking.
     *
     * @urlParam id integer required The ID of the parking. Example: 1
     * @response 204
     * @responseFile 404 docs/responses/parking/destroy-error.json
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Find the closest parking within 500 meters.
     *
     * @bodyParam Request \App\Http\Requests\Parking\ClosestParkingRequest
     * @responseFile 200 docs/responses/parking/closest-success.json
     * @responseFile 404 docs/responses/parking/closest-not-found.json
     */
    public function closest(ClosestParkingRequest $request): JsonResponse
    {
        $coords  = $request->validated();
        $parking = $this->service->findClosest(
            $coords['latitude'],
            $coords['longitude']
        );

        if (! $parking) {
            return response()->json([
                'message' => 'No parking found within 500m'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($parking);
    }
}
