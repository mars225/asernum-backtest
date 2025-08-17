<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="AHotels API",
 *     version="1.0.0",
 *     description="Documentation de l’API de gestion des hôtels, chambres, utilisateurs et réservations."
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="API base path"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerDoc {}
