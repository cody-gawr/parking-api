<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Parking;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class ParkingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        // create and authenticate a user for Parking APIs
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
    }

    #[Test]
    public function can_list_parkings()
    {
        Parking::factory()->count(3)->create();

        $this->getJson('/api/parkings')
             ->assertStatus(200)
             ->assertJsonCount(3);
    }

    #[Test]
    public function can_create_parking_with_valid_data()
    {
        $payload = [
            'name'      => 'Lot A',
            'address'   => '123 Main St',
            'latitude'  => 40.7128,
            'longitude' => -74.0060,
        ];

        $response = $this->postJson('/api/parkings', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Lot A']);

        $this->assertDatabaseHas('parkings', ['name' => 'Lot A']);
    }

    #[Test]
    public function create_parking_validation_errors_return_400()
    {
        $response = $this->postJson('/api/parkings', [
            'name' => '', // missing required fields
        ]);

        $response->assertStatus(400)
                 ->assertJsonStructure(['success', 'error', 'messages']);
    }

    #[Test]
    public function can_show_parking()
    {
        $parking = Parking::factory()->create();

        $this->getJson("/api/parkings/{$parking->id}")
             ->assertStatus(200)
             ->assertJsonFragment(['id' => $parking->id]);
    }

    #[Test]
    public function show_nonexistent_parking_returns_404()
    {
        $id = 9999;
        $this->getJson("/api/parkings/$id")
             ->assertStatus(404)
             ->assertJson([
                'success' => false,
                'error' => 'Not Found',
                'message' => "Parking #$id not found."
             ]);
    }

    #[Test]
    public function can_update_parking()
    {
        $parking = Parking::factory()->create(['name' => 'Old Name']);

        $this->putJson("/api/parkings/{$parking->id}", [
            'name' => 'New Name'
        ])->assertStatus(200)
          ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('parkings', ['name' => 'New Name']);
    }

    #[Test]
    public function can_delete_parking()
    {
        $parking = Parking::factory()->create();

        $this->deleteJson("/api/parkings/{$parking->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('parkings', ['id' => $parking->id]);
    }

    #[Test]
    public function find_closest_parking_within_500m()
    {
        // center point
        $lat = 40.7128;
        $lng = -74.0060;

        // one within ~100m
        Parking::factory()->create([
            'latitude'  => $lat + 0.0005,
            'longitude' => $lng - 0.0005,
        ]);

        // one far away
        Parking::factory()->create([
            'latitude'  => $lat + 0.01,
            'longitude' => $lng + 0.01,
        ]);

        $this->getJson("/api/parkings/closest?latitude={$lat}&longitude={$lng}")
             ->assertStatus(200)
             ->assertJsonStructure(['id','name','latitude','longitude']);
    }

    #[Test]
    public function closest_returns_404_and_sends_notification_when_no_parking_within_range()
    {
        $lat = 0;
        $lng = 0; // unlikely to have any

        $this->getJson("/api/parkings/closest?latitude={$lat}&longitude={$lng}")
             ->assertStatus(404)
             ->assertJson(['message' => 'No parking found within 500m']);

        // notification persisted
        $this->assertDatabaseHas('notifications', [
            'type' => \App\Notifications\OutOfRangeNotification::class,
            'notifiable_id' => $this->user->id,
        ]);
    }
}
