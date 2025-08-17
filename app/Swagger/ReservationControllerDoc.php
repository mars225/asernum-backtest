<?php

namespace App\Swagger;

class ReservationControllerDoc
{
    /**
     * @OA\Get(
     *     path="/reservations",
     *     summary="Liste des réservations",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des réservations"
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/reservations",
     *     summary="Créer une réservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"room_id","start_date","end_date"},
     *             @OA\Property(property="room_id", type="integer"),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="end_date", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Réservation créée"
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/reservations/{id}",
     *     summary="Afficher une réservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la réservation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réservation non trouvée"
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/reservations/{id}",
     *     summary="Mettre à jour une réservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="date_start", type="string", format="date"),
     *             @OA\Property(property="date_end", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réservation mise à jour"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réservation non trouvée"
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Put(
     *     path="/admin/reservations/{id}/start",
     *     summary="Démarrer une réservation (confirmer la réservation)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut de Réservation mis à jour"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réservation non trouvée"
     *     )
     * )
     */
    public function startReservation() {}

        /**
     * @OA\Put(
     *     path="/admin/reservations/{id}/close",
     *     summary="Démarrer une réservation (confirmer la réservation)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut de Réservation mis à jour"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réservation non trouvée"
     *     )
     * )
     */
    public function closeReservation() {}

    /**
     * @OA\Delete(
     *     path="/reservations/{id}",
     *     summary="Supprimer une réservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réservation supprimée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Réservation non trouvée"
     *     )
     * )
     */
    public function destroy() {}

    /**
     * @OA\Get(
     *     path="/hotels/{hotelId}/available-rooms",
     *     summary="Liste des chambres disponibles pour un hôtel",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="hotelId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des chambres disponibles"
     *     )
     * )
     */
    public function availableRooms() {}
}
