<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create random parkings
        Parking::factory()->count(10)->create();

        // center point for testing
        $centerLat = 40.7128;
        $centerLng = -74.0060;

        // within ~300m
        Parking::factory()->create([
            'name'      => 'Nearby Parking A',
            'address'   => '100 Test St',
            'latitude'  => $centerLat + 0.0025,
            'longitude' => $centerLng + 0.0025,
        ]);

        // within ~450m
        Parking::factory()->create([
            'name'      => 'Nearby Parking B',
            'address'   => '200 Test Ave',
            'latitude'  => $centerLat - 0.0035,
            'longitude' => $centerLng + 0.0030,
        ]);

        // outside ~1km
        Parking::factory()->create([
            'name'      => 'Distant Parking',
            'address'   => '300 Faraway Rd',
            'latitude'  => $centerLat + 0.01,
            'longitude' => $centerLng + 0.01,
        ]);
    }
}
