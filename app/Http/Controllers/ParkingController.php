<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parking\ClosestParkingRequest;
use App\Http\Requests\Parking\StoreParkingRequest;
use App\Http\Requests\Parking\UpdateParkingRequest;
use App\Services\ParkingService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * GET /api/parkings
     */
    public function index(): JsonResponse
    {
        return response()->json($this->service->list());
    }

    /**
     * POST /api/parkings
     */
    public function store(StoreParkingRequest $request): JsonResponse
    {
        $parking = $this->service->create($request->validated());
        return response()->json($parking, Response::HTTP_CREATED);
    }

    /**
     * GET /api/parkings/{id}
     */
    public function show(int $id): JsonResponse
    {
        $parking = $this->service->get($id);
        return response()->json($parking);
    }

    /**
     * PUT/PATCH /api/parkings/{id}
     */
    public function update(UpdateParkingRequest $request, int $id): JsonResponse
    {
        $parking = $this->service->update($id, $request->validated());
        return response()->json($parking);
    }

    /**
     * DELETE /api/parkings/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * GET /api/parkings/closest?latitude=...&longitude=...
     */
    public function closest(ClosestParkingRequest $request): JsonResponse
    {
        $coords  = $request->validated();
        $parking = $this->service->findClosest(
            $coords['latitude'],
            $coords['longitude']
        );

        if ( ! $parking) {
            return response()->json([
                'message' => 'No parking found within 500m'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($parking);
    }
}
