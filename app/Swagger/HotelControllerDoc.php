<?php

namespace App\Swagger;

class HotelControllerDoc
{
    /**
     * @OA\Get(
     *     path="/hotels",
     *     summary="Liste des hôtels",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="label",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des hôtels"
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/admin/hotels",
     *     summary="Créer un hôtel",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"label","code"},
     *             @OA\Property(property="label", type="string"),
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="country", type="string"),
     *             @OA\Property(property="stars", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Hôtel créé avec succès"
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/hotels/{id}",
     *     summary="Afficher un hôtel",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'hôtel"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hôtel non trouvé"
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/admin/hotels/{id}",
     *     summary="Mettre à jour un hôtel",
     *     tags={"Hotels"},
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
     *             @OA\Property(property="_method", type="string", example="PUT"),
     *             @OA\Property(property="label", type="string"),
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="country", type="string"),
     *             @OA\Property(property="stars", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hôtel mis à jour avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hôtel non trouvé"
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/admin/hotels/{id}",
     *     summary="Supprimer un hôtel",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hôtel supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hôtel non trouvé"
     *     )
     * )
     */
    public function destroy() {}
}
