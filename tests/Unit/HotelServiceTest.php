<?php

namespace Tests\Unit;

use App\Models\Hotel;
use App\Repositories\HotelRepository;
use App\Services\HotelService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use PHPUnit\Framework\TestCase;

class HotelServiceTest extends TestCase
{
    protected $service;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(HotelRepository::class);
        $this->service = new HotelService($this->repository);
    }


    /** @test */
    public function it_lists_hotels()
    {
        $this->repository
            ->shouldReceive('getAll')
            ->with(15, [])
            ->andReturn(['hotel1', 'hotel2']);

        $result = $this->service->listHotels();

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_returns_a_hotel_when_found()
    {
        $hotel = new Hotel(['id' => 1, 'label' => 'Test Hotel']);

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($hotel);

        $result = $this->service->getHotel(1);

        $this->assertEquals('Test Hotel', $result->label);
    }

    /** @test */
    public function it_throws_exception_when_hotel_not_found()
    {
        $this->repository
            ->shouldReceive('findById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->getHotel(999);
    }

    /** @test */
    public function it_creates_a_hotel()
    {
        $data = ['label' => 'New Hotel'];

        $this->repository
            ->shouldReceive('create')
            ->with($data)
            ->andReturn(new Hotel($data));

        $result = $this->service->createHotel($data);

        $this->assertEquals('New Hotel', $result->label);
    }

    /** @test */
    public function it_updates_a_hotel()
    {
        $hotel = new Hotel(['id' => 1, 'label' => 'Old Hotel']);
        $data = ['label' => 'Updated Hotel'];

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($hotel);

        $this->repository
            ->shouldReceive('update')
            ->with($hotel, $data)
            ->andReturn(new Hotel(array_merge($hotel->toArray(), $data)));

        $result = $this->service->updateHotel(1, $data);

        $this->assertEquals('Updated Hotel', $result->label);
    }

    /** @test */
    public function it_throws_exception_when_updating_non_existent_hotel()
    {
        $this->repository
            ->shouldReceive('findById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->updateHotel(999, ['label' => 'Should Fail']);
    }

    /** @test */
    public function it_deletes_a_hotel()
    {
        $hotel = new Hotel(['id' => 1, 'label' => 'To Delete']);

        $this->repository
            ->shouldReceive('findById')
            ->with(1)
            ->andReturn($hotel);

        $this->repository
            ->shouldReceive('delete')
            ->with($hotel)
            ->andReturn(true);

        $this->service->deleteHotel(1);

        $this->assertTrue(true); // si aucune exception levée = test OK
    }

    /** @test */
    public function it_throws_exception_when_deleting_non_existent_hotel()
    {
        $this->repository
            ->shouldReceive('findById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->deleteHotel(999);
    }
}
