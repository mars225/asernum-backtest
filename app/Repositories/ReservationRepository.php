<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationRepository
{
    public function getAll($perPage = 15, $filters = [])
    {
        $query = Reservation::query();
        $user = Auth::user();
        // Si l'utilisateur n'est pas admin, ne récupérer que ses propres réservations
        if ($user->role !== 'admin') {
            $query->where('customer_id', $user->id);
        }

        if (!empty($filters['customer_id']) && $user->role === 'admin') {
            $query->where('customer_id', $filters['customer_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('start_date')->paginate($perPage);
    }

    public function findById($id)
    {
        return Reservation::find($id);
    }

    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data)
    {
        $reservation->update($data);
        return $reservation;
    }

    public function delete(Reservation $reservation)
    {
        $reservation->delete();
    }

    public function checkAvailability($roomId, $startDate, $endDate, $excludeReservationId = null)
    {
        $query = Reservation::where('room_id', $roomId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }

    public function findAvailableRooms($hotelId, $startDate = null, $endDate = null)
    {
        $query = Room::where('hotel_id', $hotelId);

        if ($startDate && $endDate) {
            $query->whereDoesntHave('reservations', function ($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'cancelled')
                    ->where(function ($q2) use ($startDate, $endDate) {
                        $q2->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($q3) use ($startDate, $endDate) {
                                $q3->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                            });
                    });
            });
        }

        return $query->get();
    }
}
