<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CloseReservationRequest;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Http\Requests\Reservation\StartReservationRequest;
use App\Http\Requests\Reservation\UpdateStatusReservationRequest;
use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Http\Requests\ReservationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReservationController extends Controller
{
    protected $service;

    public function __construct(ReservationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $reservations = $this->service->listReservations(
            $request->get('per_page', 15),
            $request->only(['customer_id', 'status'])
        );

        return response()->json($reservations);
    }

    public function store(CreateReservationRequest $request)
    {
        try {
            $reservation = $this->service->createReservation($request->validated());
            return response()->json(['message' => 'Réservation créée', 'data' => $reservation], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        try {
            $reservation = $this->service->getReservation($id);
            return response()->json(['message' => 'Succès', 'data' => $reservation]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(ReservationRequest $request, $id)
    {
        try {
            $reservation = $this->service->updateReservation($id, $request->validated());
            return response()->json(['message' => 'Réservation mise à jour', 'data' => $reservation]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function startReservation(StartReservationRequest $request, $id)
    {
        try {
            $reservation = $this->service->updateStatusReservation($id, $request->validated());
            return response()->json(['message' => 'Réservation mise à jour', 'data' => $reservation]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function closeReservation(CloseReservationRequest $request, $id)
    {
        try {
            $reservation = $this->service->updateStatusReservation($id, $request->validated());
            return response()->json(['message' => 'Réservation mise à jour', 'data' => $reservation]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteReservation($id);
            return response()->json(['message' => 'Réservation supprimée']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    public function availableRooms($hotelId, Request $request)
    {
        // Exemple simple : à adapter selon ta logique métier
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Appelle un service ou une requête pour récupérer les chambres disponibles
        $rooms = $this->service->getAvailableRooms($hotelId, $startDate, $endDate);

        return response()->json(['data' => $rooms]);
    }
}
