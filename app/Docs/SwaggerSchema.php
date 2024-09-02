<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="prenom", type="string", example="John"),
 *     @OA\Property(property="nom", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="role", type="string", example="admin")
 * )
 */
class SwaggerSchema
{
    // Ce fichier peut rester vide ou contenir des méthodes statiques si nécessaire
}
