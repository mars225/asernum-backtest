<?php

namespace App\Swagger;

class RoomControllerDoc
{
    /**
     * @OA\Get(
     *     path="/hotels/{hotelId}/rooms",
     *     summary="Liste des chambres d'un hôtel",
     *     tags={"Rooms"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="hotelId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des chambres"
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/admin/hotels/{hotelId}/rooms",
     *     summary="Créer une chambre dans un hôtel",
     *     tags={"Rooms"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="hotelId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"room_label","number","type","price_per_night","occupants","available"},
     *             @OA\Property(property="room_label", type="string"),
     *             @OA\Property(property="number", type="string", maxLength=20),
     *             @OA\Property(property="type", type="string", enum={"single", "double", "suite"}),
     *             @OA\Property(property="price_per_night", type="number", format="float", minimum=0),
     *             @OA\Property(property="occupants", type="integer", minimum=1),
     *             @OA\Property(property="available", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chambre créée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/rooms/{id}",
     *     summary="Afficher une chambre",
     *     tags={"Rooms"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la chambre"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chambre non trouvée"
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/admin/rooms/{id}",
     *     summary="Mettre à jour une chambre",
     *     tags={"Rooms"},
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
     *             @OA\Property(property="room_label", type="string"),
     *             @OA\Property(property="number", type="string", maxLength=20),
     *             @OA\Property(property="type", type="string", enum={"single", "double", "suite"}),
     *             @OA\Property(property="price_per_night", type="number", format="float", minimum=0),
     *             @OA\Property(property="occupants", type="integer", minimum=1),
     *             @OA\Property(property="available", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chambre mise à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chambre non trouvée"
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/admin/rooms/{id}",
     *     summary="Supprimer une chambre",
     *     tags={"Rooms"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chambre supprimée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chambre non trouvée"
     *     )
     * )
     */
    public function destroy() {}
}
