<?php

namespace App\Services;

use App\Repositories\ReservationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    protected $repository;

    public function __construct(ReservationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listReservations($perPage = 15, $filters = [])
    {
        return $this->repository->getAll($perPage, $filters);
    }

    public function getReservation($id)
    {
        $reservation = $this->repository->findById($id);
        if (!$reservation) {
            throw new ModelNotFoundException("Réservation non trouvée");
        }
        $user = Auth::user();
        $authResult = Gate::forUser($user)->inspect('view', $reservation);
        if (!$authResult->allowed()) {
            return response()->json(['message' => $authResult->message()], 403);
        }

        return $reservation;
    }

    public function createReservation(array $data)
    {
        // Vérification disponibilité
        $available = $this->repository->checkAvailability($data['room_id'], $data['start_date'], $data['end_date']);
        if (!$available) {
            throw new \Exception("La chambre n'est pas disponible pour ces dates.");
        }

        return $this->repository->create($data);
    }

    public function updateReservation($id, array $data)
    {
        $reservation = $this->repository->findById($id);
        if (!$reservation) {
            throw new ModelNotFoundException("Réservation non trouvée");
        }

        // Vérification disponibilité (exclure cette réservation)
        $available = $this->repository->checkAvailability($reservation->room_id, $data['start_date'], $data['end_date'], $id);
        if (!$available) {
            throw new \Exception("La chambre n'est pas disponible pour ces dates.");
        }

        return $this->repository->update($reservation, $data);
    }

    public function updateStatusReservation($id, array $data)
    {
        $reservation = $this->repository->findById($id);
        if (!$reservation) {
            throw new ModelNotFoundException("Réservation non trouvée");
        }
        if (!$reservation->canChangeStatus($data['status'])) {
            throw ValidationException::withMessages([
                'status' => [$reservation->statusChangeMessage($data['status'])]
            ]);
        }
        return $this->repository->update($reservation, $data);
    }

    public function deleteReservation($id)
    {
        $reservation = $this->repository->findById($id);
        if (!$reservation) {
            throw new ModelNotFoundException("Réservation non trouvée");
        }
        $user = Auth::user();
        $newStatus = 'cancelled';
        $authResult = Gate::forUser($user)->inspect('updateStatus', [$reservation, $newStatus]);
        if (!$authResult->allowed()) {
            return response()->json(['message' => $authResult->message()], 403);
        }
        $reservation->status = $newStatus; // marquer comme annulée
        $reservation->save();
        //$this->repository->delete($reservation);
    }

    public function getAvailableRooms($hotelId, $startDate = null, $endDate = null)
    {
        // À adapter selon ta logique, ici un exemple basique
        return $this->repository->findAvailableRooms($hotelId, $startDate, $endDate);
    }
}
