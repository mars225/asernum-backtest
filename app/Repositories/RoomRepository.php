<?php
namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function getByHotel($hotelId, $perPage = 15, $filters = [])
    {
        $query = Room::where('hotel_id', $hotelId);

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('number')->paginate($perPage);
    }

    public function findById($id)
    {
        return Room::find($id);
    }

    public function create(array $data)
    {
        return Room::create($data);
    }

    public function update(Room $room, array $data)
    {
        $room->update($data);
        return $room;
    }

    public function delete(Room $room)
    {
        $room->delete();
    }
}
