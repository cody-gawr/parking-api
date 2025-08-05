<?php

namespace App\Repositories;

use App\Models\Parking;
use Illuminate\Database\Eloquent\Collection;

class ParkingRepository
{
    protected Parking $model;

    public function __construct(Parking $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Parking
    {
        return $this->model->find($id);
    }

    public function create(array $attributes): Parking
    {
        return $this->model->create($attributes);
    }

    public function update(Parking $parking, array $attributes): Parking
    {
        $parking->update($attributes);
        return $parking;
    }

    public function delete(Parking $parking): bool
    {
        return $parking->delete();
    }

    /**
     * Return all parkings within $meters of ($lat,$lng), ordered by distance asc.
     */
    public function getWithinDistance(float $lat, float $lng, float $meters): Collection
    {
        // Haversine formula (distance in meters on earth radius 6,371 km)
        $haversine = "(6371000 * acos(
            cos(radians(?))
            * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?))
            * sin(radians(latitude))
        ))";

        return $this->model
            ->select('*')
            ->selectRaw("$haversine AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $meters)
            ->orderBy('distance', 'asc')
            ->get();
    }
}
