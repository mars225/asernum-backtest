<?php

namespace App\Swagger;

class UserControllerDoc
{

    /**
     * @OA\Get(
     *     path="/admin/users",
     *     summary="Liste des utilisateurs",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs"
     *     )
     * )
     */
    function index() {}


    /**
     * @OA\Post(
     *     path="/admin/users",
     *     summary="Créer un utilisateur",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","username","email","password","mobile","role"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="mobile", type="string"),
     *             @OA\Property(property="role", type="string", enum={"admin", "customer"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé"
     *     )
     * )
     */
    function store() {}

    /**
     * @OA\Get(
     *     path="/admin/users/{id}",
     *     summary="Afficher un utilisateur",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails utilisateur"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
    function show() {}




    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Inscription client",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","name","mobile","email","password", "password_confirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="mobile", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inscription réussie"
     *     )
     * )
     */
    function register() {}

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Connexion utilisateur",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password"},
     *             @OA\Property(property="login", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    function login() {}

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Déconnexion utilisateur",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie"
     *     )
     * )
     */
    function logout() {}

    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Profil utilisateur connecté",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Profil utilisateur"
     *     )
     * )
     */
    function me() {}
}
