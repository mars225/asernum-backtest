<?php
namespace App\Services;

use App\Repositories\RoomRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomService
{
    protected $repository;

    public function __construct(RoomRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listRooms($hotelId, $perPage = 15, $filters = [])
    {
        return $this->repository->getByHotel($hotelId, $perPage, $filters);
    }

    public function getRoom($id)
    {
        $room = $this->repository->findById($id);
        if (!$room) {
            throw new ModelNotFoundException("Chambre non trouvée");
        }
        return $room;
    }

    public function createRoom(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateRoom($id, array $data)
    {
        $room = $this->repository->findById($id);
        if (!$room) {
            throw new ModelNotFoundException("Chambre non trouvée");
        }
        return $this->repository->update($room, $data);
    }

    public function deleteRoom($id)
    {
        $room = $this->repository->findById($id);
        if (!$room) {
            throw new ModelNotFoundException("Chambre non trouvée");
        }
        $this->repository->delete($room);
    }
}
