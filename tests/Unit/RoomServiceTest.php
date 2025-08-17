<?php

namespace Tests\Unit;

use App\Services\RoomService;
use App\Repositories\RoomRepository;
use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;

class RoomServiceTest extends TestCase
{
    protected $service;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(RoomRepository::class);
        $this->service = new RoomService($this->repository);
    }

    /** @test */
    public function it_lists_rooms_by_hotel()
    {
        $this->repository
            ->shouldReceive('getByHotel')
            ->with(1, 15, [])
            ->andReturn(['room1', 'room2']);

        $result = $this->service->listRooms(1);

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_returns_a_room_when_found()
    {
        $room = new Room(['id' => 1, 'number' => '101']);

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($room);

        $result = $this->service->getRoom(1);

        $this->assertEquals('101', $result->number);
    }

    /** @test */
    public function it_throws_exception_when_room_not_found()
    {
        $this->repository
            ->shouldReceive('findById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->getRoom(999);
    }

    /** @test */
    public function it_creates_a_room()
    {
        $data = ['number' => '202', 'hotel_id' => 1];

        $this->repository
            ->shouldReceive('create')
            ->with($data)
            ->andReturn(new Room($data));

        $result = $this->service->createRoom($data);

        $this->assertEquals('202', $result->number);
    }

    /** @test */
    public function it_updates_a_room()
    {
        $room = new Room(['id' => 1, 'number' => '303']);
        $data = ['number' => '305'];

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($room);

        $this->repository
            ->shouldReceive('update')
            ->with($room, $data)
            ->andReturn(new Room(array_merge($room->toArray(), $data)));

        $result = $this->service->updateRoom(1, $data);

        $this->assertEquals('305', $result->number);
    }

    /** @test */
    public function it_throws_exception_when_updating_non_existent_room()
    {
        $this->repository
            ->shouldReceive('findById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->updateRoom(999, ['number' => 'Should Fail']);
    }

    /** @test */
    public function it_deletes_a_room()
    {
        $room = new Room(['id' => 1, 'number' => '404']);

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($room);

        $this->repository
            ->shouldReceive('delete')
            ->with($room)
            ->andReturn(true);

        $this->service->deleteRoom(1);

        $this->assertTrue(true); // si aucune exception levée = succès
    }

    /** @test */
    public function it_throws_exception_when_deleting_non_existent_room()
    {
        $this->repository
            ->shouldReceive('findById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->deleteRoom(999);
    }
}
