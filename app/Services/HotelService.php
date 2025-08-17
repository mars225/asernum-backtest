<?php
namespace App\Services;

use App\Repositories\HotelRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HotelService
{
    protected $repository;

    public function __construct(HotelRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listHotels($perPage = 15, $filters = [])
    {
        return $this->repository->getAll($perPage, $filters);
    }

    public function getHotel($id)
    {
        $hotel = $this->repository->findById($id);
        if (!$hotel) {
            throw new ModelNotFoundException("Hôtel non trouvé");
        }
        return $hotel;
    }

    public function createHotel(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateHotel($id, array $data)
    {
        $hotel = $this->repository->findById($id);
        if (!$hotel) {
            throw new ModelNotFoundException("Hôtel non trouvé");
        }
        return $this->repository->update($hotel, $data);
    }

    public function deleteHotel($id)
    {
        $hotel = $this->repository->findById($id);
        if (!$hotel) {
            throw new ModelNotFoundException("Hôtel non trouvé");
        }
        $this->repository->delete($hotel);
    }
}
