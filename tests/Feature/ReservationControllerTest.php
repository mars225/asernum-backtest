<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake user authentifié
        /*$user = User::factory()->create(['id' => 1]);
        $this->actingAs($user, 'sanctum');*/

        $this->service = Mockery::mock(ReservationService::class);
        $this->app->instance(ReservationService::class, $this->service);
    }

    /** @test */
    public function it_lists_reservations()
    {
        $user = User::factory()->create([
            'id' => 1,
            'role' => 'customer',
        ]);
        $this->actingAs($user, 'sanctum');
        $this->service
            ->shouldReceive('listReservations')
            ->with(15, [])
            ->andReturn(['res1', 'res2']);

        $response = $this->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJson(['res1', 'res2']);
    }

    /** @test */
    public function it_creates_reservation_successfully()
    {
        // Créer un utilisateur avec le rôle customer pour ce test uniquement
        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user, 'sanctum');

        $hotel = Hotel::factory()->create();

        $room = Room::factory()->create([
            'hotel_id' => $hotel->id,
            'available' => true,
        ]);

        $data = [
            'room_id' => $room->id,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
        ];

        $this->service
            ->shouldReceive('createReservation')
            ->with(Mockery::on(fn($arg) => $arg['room_id'] === $room->id))
            ->andReturn(new Reservation(array_merge($data, [
                'customer_id' => $user->id,
                'status' => 'pending',
            ])));

        $response = $this->postJson('/api/reservations', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Réservation créée']);
    }

    /** @test */
    public function it_returns_422_if_reservation_creation_fails()
    {
        $user = User::factory()->create([
            'id' => 1,
            'role' => 'customer',
        ]);
        $this->actingAs($user, 'sanctum');
        $hotel = Hotel::factory()->create();

        $room = Room::factory()->create([
            'hotel_id' => $hotel->id,
            'available' => true,
        ]);

        $data = [
            'room_id' => $room->id,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
        ];

        $this->service
            ->shouldReceive('createReservation')
            ->andThrow(new \Exception("Erreur de création"));

        $response = $this->postJson('/api/reservations', $data);

        $response->assertStatus(422)
            ->assertJsonFragment(['message' => 'Erreur de création']);
    }

    /** @test */
    public function it_shows_reservation_if_found()
    {
        $user = User::factory()->create([
            'id' => 1,
            'role' => 'customer',
        ]);
        $this->actingAs($user, 'sanctum');
        $reservation = new Reservation(['id' => 1]);

        $this->service
            ->shouldReceive('getReservation')
            ->with(1)
            ->andReturn($reservation);

        $response = $this->getJson('/api/reservations/1');

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Succès']);
    }

    /** @test */
    public function it_returns_404_if_reservation_not_found()
    {
        $user = User::factory()->create([
            'id' => 1,
            'role' => 'customer',
        ]);
        $this->actingAs($user, 'sanctum');
        $this->service
            ->shouldReceive('getReservation')
            ->andThrow(new ModelNotFoundException("Réservation non trouvée"));

        $response = $this->getJson('/api/reservations/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Réservation non trouvée']);
    }




    /** @test */
    public function it_deletes_reservation_successfully()
    {
        $user = User::factory()->create([
            'id' => 1,
            'role' => 'admin',
        ]);
        $this->actingAs($user, 'sanctum');
        $this->service
            ->shouldReceive('deleteReservation')
            ->with(1)
            ->andReturnTrue();

        $response = $this->deleteJson('/api/reservations/1');

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Réservation supprimée']);
    }

    /** @test */
    public function it_returns_404_if_delete_not_found()
    {
        $user = User::factory()->create([
            'id' => 1,
            'role' => 'customer',
        ]);
        $this->actingAs($user, 'sanctum');
        $this->service
            ->shouldReceive('deleteReservation')
            ->andThrow(new ModelNotFoundException("Réservation non trouvée"));

        $response = $this->deleteJson('/api/reservations/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Réservation non trouvée']);
    }

}
