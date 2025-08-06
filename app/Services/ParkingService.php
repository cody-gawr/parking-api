<?php

namespace App\Services;

use App\Repositories\ParkingRepository;
use App\Repositories\NotificationRepository;
use App\Models\Parking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OutOfRangeNotification;
use Illuminate\Database\Eloquent\Collection;

class ParkingService
{
    public function __construct(
        protected ParkingRepository $parkingRepo,
        protected NotificationRepository $notificationRepo
    ) {
    }

    public function list(): Collection
    {
        return $this->parkingRepo->all();
    }

    public function get(int $id): Parking
    {
        return $this->parkingRepo->find($id)
            ?? throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Parking #{$id} not found.");
    }

    public function create(array $data): Parking
    {
        return $this->parkingRepo->create($data);
    }

    public function update(int $id, array $data): Parking
    {
        $parking = $this->get($id);
        return $this->parkingRepo->update($parking, $data);
    }

    public function delete(int $id): void
    {
        $parking = $this->get($id);
        $this->parkingRepo->delete($parking);
    }

    /**
     * Find the nearest parking within $radiusMeters.
     * If none found, persist & broadcast a notification and return null.
     */
    public function findClosest(
        float $lat,
        float $lng,
        int $radiusMeters = 500
    ): ?Parking {
        $nearby = $this->parkingRepo->getWithinDistance($lat, $lng, $radiusMeters);

        if ($nearby->isEmpty()) {
            Log::warning("No parking within {$radiusMeters}m of ({$lat},{$lng})");

            if ($user = Auth::user()) {
                // send user a notification
                $user->notify(new OutOfRangeNotification($lat, $lng));
            }

            return null;
        }

        return $nearby->first();
    }
}
