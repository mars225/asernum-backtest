<?php
namespace App\Repositories;

use App\Models\Hotel;

class HotelRepository
{
    public function getAll($perPage = 15, $filters = [])
    {
        $query = Hotel::query();

        if (!empty($filters['label'])) {
            $query->where('label', 'ilike', "%{$filters['label']}%");
        }
        if (!empty($filters['city'])) {
            $query->where('city', 'ilike', "%{$filters['city']}%");
        }

        return $query->orderBy('label')->paginate($perPage);
    }

    public function findById($id)
    {
        return Hotel::find($id);
    }

    public function create(array $data)
    {
        return Hotel::create($data);
    }

    public function update(Hotel $hotel, array $data)
    {
        $hotel->update($data);
        return $hotel;
    }

    public function delete(Hotel $hotel)
    {
        $hotel->delete();
    }
}
