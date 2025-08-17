<?php

namespace Tests\Unit;

use App\Services\ReservationService;
use App\Repositories\ReservationRepository;
use App\Models\Reservation;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    protected $service;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(ReservationRepository::class);
        $this->service = new ReservationService($this->repository);

        // mock Auth::user() pour tous les tests
        Auth::shouldReceive('user')->andReturn((object)['id' => 1]);
    }

    /** @test */
    public function it_lists_reservations()
    {
        $this->repository
            ->shouldReceive('getAll')
            ->with(15, [])
            ->andReturn(['res1', 'res2']);

        $result = $this->service->listReservations();

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_returns_reservation_when_found_and_authorized()
    {
        $reservation = new Reservation(['id' => 1]);

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($reservation);

        Gate::shouldReceive('forUser')
            ->with(Auth::user())
            ->andReturnSelf();

        Gate::shouldReceive('inspect')
            ->with('view', $reservation)
            ->andReturn(Response::allow());

        $result = $this->service->getReservation(1);

        $this->assertInstanceOf(Reservation::class, $result);
    }

    /** @test */
    public function it_denies_access_if_gate_rejects_reservation_view()
    {
        $reservation = new Reservation(['id' => 1]);

        $this->repository->shouldReceive('findById')->with(1)->andReturn($reservation);

        Gate::shouldReceive('forUser')
            ->with(Auth::user())
            ->andReturnSelf();

        Gate::shouldReceive('inspect')
            ->with('view', $reservation)
            ->andReturn(Response::deny('Vous n’êtes pas autorisé à voir cette réservation.'));

        $response = $this->service->getReservation(1);

        $this->assertEquals(403, $response->status());
        $this->assertEquals(
            'Vous n’êtes pas autorisé à voir cette réservation.',
            $response->getData()->message
        );
    }

    /** @test */
    public function it_throws_if_reservation_not_found()
    {
        $this->repository->shouldReceive('findById')->with(999)->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->getReservation(999);
    }

    /** @test */
    public function it_creates_reservation_if_available()
    {
        $data = ['room_id' => 1, 'start_date' => '2025-01-01', 'end_date' => '2025-01-05'];

        $this->repository->shouldReceive('checkAvailability')->andReturn(true);
        $this->repository->shouldReceive('create')->with($data)->andReturn(new Reservation($data));

        $result = $this->service->createReservation($data);

        $this->assertInstanceOf(Reservation::class, $result);
    }

    /** @test */
    public function it_throws_if_room_not_available_on_create()
    {
        $data = ['room_id' => 1, 'start_date' => '2025-01-01', 'end_date' => '2025-01-05'];

        $this->repository->shouldReceive('checkAvailability')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->service->createReservation($data);
    }

    /** @test */
    public function it_updates_reservation_if_available()
    {
        $reservation = new Reservation(['id' => 1, 'room_id' => 10]);
        $data = ['start_date' => '2025-02-01', 'end_date' => '2025-02-05'];

        $this->repository->shouldReceive('findById')->with(1)->andReturn($reservation);
        $this->repository->shouldReceive('checkAvailability')->andReturn(true);
        $this->repository->shouldReceive('update')->with($reservation, $data)->andReturn(new Reservation($data));

        $result = $this->service->updateReservation(1, $data);

        $this->assertEquals('2025-02-01', $result->start_date);
    }

    /** @test */
    public function it_throws_if_room_not_available_on_update()
    {
        $reservation = new Reservation(['id' => 1, 'room_id' => 10]);
        $data = ['start_date' => '2025-02-01', 'end_date' => '2025-02-05'];

        $this->repository->shouldReceive('findById')->andReturn($reservation);
        $this->repository->shouldReceive('checkAvailability')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->service->updateReservation(1, $data);
    }

    /** @test */
    public function it_updates_status_if_allowed()
    {
        $reservation = Mockery::mock(Reservation::class)->makePartial();
        $reservation->shouldReceive('canChangeStatus')->with('confirmed')->andReturn(true);

        $this->repository->shouldReceive('findById')->with(1)->andReturn($reservation);
        $this->repository->shouldReceive('update')->with($reservation, ['status' => 'confirmed'])->andReturn($reservation);

        $result = $this->service->updateStatusReservation(1, ['status' => 'confirmed']);

        $this->assertInstanceOf(Reservation::class, $result);
    }

    /** @test */
    public function it_throws_validation_if_status_not_allowed()
    {
        $reservation = Mockery::mock(Reservation::class)->makePartial();
        $reservation->shouldReceive('canChangeStatus')->with('cancelled')->andReturn(false);
        $reservation->shouldReceive('statusChangeMessage')->andReturn('Not allowed');

        $this->repository->shouldReceive('findById')->andReturn($reservation);

        $this->expectException(ValidationException::class);

        $this->service->updateStatusReservation(1, ['status' => 'cancelled']);
    }

    /** @test */
    public function it_deletes_reservation_if_authorized()
    {
        $reservation = Mockery::mock(Reservation::class)->makePartial();
        $reservation->status = 'active';

        $this->repository->shouldReceive('findById')->with(1)->andReturn($reservation);

        Gate::shouldReceive('forUser->inspect')
            ->andReturn(Response::allow());

        $reservation->shouldReceive('save')->once();

        $this->service->deleteReservation(1);

        $this->assertEquals('cancelled', $reservation->status);
    }

    /** @test */
    public function it_denies_deletion_if_not_authorized()
    {
        $reservation = new Reservation(['id' => 1]);

        $this->repository->shouldReceive('findById')->with(1)->andReturn($reservation);

        Gate::shouldReceive('forUser->inspect')
            ->andReturn(Response::deny('Forbidden'));

        $response = $this->service->deleteReservation(1);

        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_returns_available_rooms()
    {
        $this->repository
            ->shouldReceive('findAvailableRooms')
            ->with(1, '2025-03-01', '2025-03-10')
            ->andReturn(['roomA', 'roomB']);

        $result = $this->service->getAvailableRooms(1, '2025-03-01', '2025-03-10');

        $this->assertCount(2, $result);
    }
}
