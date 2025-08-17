<?php

use Tests\TestCase;
use Mockery;
use App\Services\ReservationService;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ReservationControllerTest extends TestCase
{
    protected $service;
    protected $userMock;
    protected $hotelMock;
    protected $roomMock;
    protected $reservationMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock du service
        $this->service = Mockery::mock(ReservationService::class);
        $this->app->instance(ReservationService::class, $this->service);

        // Mocks des modèles
        $this->userMock = Mockery::mock(User::class);
        $this->hotelMock = Mockery::mock(Hotel::class);
        $this->roomMock = Mockery::mock(Room::class);
        $this->reservationMock = Mockery::mock(Reservation::class);
    }

    /** @test */
    public function it_lists_reservations()
    {
        // Mock de l'utilisateur authentifié
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('customer');
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
    /** @test */
    public function it_creates_reservation_successfully()
    {
        // Créer un objet utilisateur simple sans Eloquent
        $user = new \stdClass();
        $user->id = 1;
        $user->role = 'customer';

        // Activer l'authentification avec ce user
        $this->actingAs($user, 'sanctum');

        // Mock complet de DB pour les validations exists du FormRequest
        DB::shouldReceive('table')->andReturnSelf();
        DB::shouldReceive('where')->andReturnSelf();
        DB::shouldReceive('exists')->andReturn(true);
        DB::shouldReceive('first')->andReturn((object)['id' => 1]);

        // Données d'entrée (sans customer_id ni status car ajoutés automatiquement)
        $data = [
            'room_id' => 1,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
        ];

        // Mock de la réservation créée
        $reservation = Mockery::mock(Reservation::class);
        $reservation->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $reservation->shouldReceive('getAttribute')->with('customer_id')->andReturn(1);
        $reservation->shouldReceive('getAttribute')->with('room_id')->andReturn(1);
        $reservation->shouldReceive('getAttribute')->with('status')->andReturn('pending');

        // Le service doit recevoir les données avec customer_id et status ajoutés par prepareForValidation()
        $this->service
            ->shouldReceive('createReservation')
            ->with(Mockery::on(function ($arg) {
                return $arg['room_id'] === 1
                    && $arg['customer_id'] === 1
                    && $arg['status'] === 'pending'
                    && $arg['start_date'] === '2025-01-01'
                    && $arg['end_date'] === '2025-01-05';
            }))
            ->andReturn($reservation);

        $response = $this->postJson('/api/reservations', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Réservation créée']);
    }

    /** @test */
    public function it_returns_422_if_reservation_creation_fails()
    {
        // Mock de l'utilisateur customer
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('customer');
        $this->actingAs($user, 'sanctum');

        $data = [
            'room_id' => 1,
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
        // Mock de l'utilisateur customer
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('customer');
        $this->actingAs($user, 'sanctum');

        // Mock de la réservation trouvée
        $reservation = Mockery::mock(Reservation::class);
        $reservation->shouldReceive('getAttribute')->with('id')->andReturn(1);

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
        // Mock de l'utilisateur customer
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('customer');
        $this->actingAs($user, 'sanctum');

        $this->service
            ->shouldReceive('getReservation')
            ->with(999)
            ->andThrow(new ModelNotFoundException("Réservation non trouvée"));

        $response = $this->getJson('/api/reservations/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Réservation non trouvée']);
    }

    /** @test */
    public function it_deletes_reservation_successfully()
    {
        // Mock de l'utilisateur admin
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('admin');
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
        // Mock de l'utilisateur customer
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('customer');
        $this->actingAs($user, 'sanctum');

        $this->service
            ->shouldReceive('deleteReservation')
            ->with(999)
            ->andThrow(new ModelNotFoundException("Réservation non trouvée"));

        $response = $this->deleteJson('/api/reservations/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Réservation non trouvée']);
    }

    /** @test */
    public function it_starts_reservation_successfully()
    {
        // Mock de l'utilisateur admin
        $admin = Mockery::mock(User::class);
        $admin->shouldReceive('getAttribute')->with('id')->andReturn(2);
        $admin->shouldReceive('getAttribute')->with('role')->andReturn('admin');
        $this->actingAs($admin, 'sanctum');

        // Mock de la réservation mise à jour
        $reservation = Mockery::mock(Reservation::class);
        $reservation->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $reservation->shouldReceive('getAttribute')->with('status')->andReturn('confirmed');

        $this->service
            ->shouldReceive('updateStatusReservation')
            ->with(1, ['status' => 'confirmed'])
            ->andReturn($reservation);

        $response = $this->putJson("/api/admin/reservations/1/start");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Réservation mise à jour']);
    }

    /** @test */
    public function it_closes_reservation_successfully()
    {
        // Mock de l'utilisateur admin
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('admin');
        $this->actingAs($user, 'sanctum');

        // Mock de la réservation fermée
        $reservation = Mockery::mock(Reservation::class);
        $reservation->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $reservation->shouldReceive('getAttribute')->with('status')->andReturn('closed');

        $this->service
            ->shouldReceive('updateStatusReservation')
            ->with(1, ['status' => 'closed'])
            ->andReturn($reservation);

        $response = $this->putJson('/api/admin/reservations/1/close', ['status' => 'closed']);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Réservation mise à jour']);
    }

    /** @test */
    public function it_returns_available_rooms()
    {
        // Mock de l'utilisateur customer
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('customer');
        $this->actingAs($user, 'sanctum');

        $this->service
            ->shouldReceive('getAvailableRooms')
            ->with(1, '2025-03-01', '2025-03-10')
            ->andReturn(['roomA', 'roomB']);

        $response = $this->getJson('/api/hotels/1/available-rooms?start_date=2025-03-01&end_date=2025-03-10');

        $response->assertStatus(200)
            ->assertJsonFragment(['roomA', 'roomB']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
